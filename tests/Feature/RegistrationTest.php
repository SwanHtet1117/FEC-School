<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\LoginOtpNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_contains_a_working_account_form(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('Create Account')
            ->assertSee('action="'.route('register.store').'"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('name="information_confirmed"', false);
    }

    public function test_visitor_can_register_a_regular_account_pending_admin_approval(): void
    {
        Notification::fake();

        $this->post('/register', [
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'phone' => '+95 123456789',
            'password' => 'a-strong-password',
            'password_confirmation' => 'a-strong-password',
            'information_confirmed' => '1',
            'is_admin' => '1',
        ])->assertRedirect(route('verification.notice'))
            ->assertSessionHas('status', 'A verification link has been sent. Verify your email before signing in; admin approval is also required.')
            ->assertSessionHas('verificationEmail', 'ada@example.com');

        $user = User::where('email', 'ada@example.com')->firstOrFail();

        $this->assertSame('Ada Lovelace', $user->name);
        $this->assertSame('+95 123456789', $user->phone);
        $this->assertFalse($user->is_admin);
        $this->assertFalse($user->is_approved);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue(Hash::check('a-strong-password', $user->password));
        Notification::assertSentTo($user, VerifyEmailNotification::class);
        $this->assertGuest();
        $this->get(route('verification.notice'))
            ->assertOk()
            ->assertSee('ada@example.com');
    }

    public function test_signed_verification_link_verifies_email_without_bypassing_admin_approval(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'is_approved' => false,
        ]);
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire')),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $this->get($verificationUrl)
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Your email address is verified. Your account is awaiting admin approval.');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_unsigned_verification_link_is_rejected(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->get(route('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]))->assertForbidden();

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_unverified_user_cannot_sign_in_and_can_request_another_link(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'email_verified_at' => null,
            'is_approved' => true,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'Please verify your email address before signing in.']);

        $this->assertGuest();
        Notification::assertNotSentTo($user, LoginOtpNotification::class);

        $this->post(route('verification.send'), ['email' => $user->email])
            ->assertRedirect(route('verification.notice'))
            ->assertSessionHas('status', 'If that email needs verification, a new link has been sent.');

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    public function test_registration_rejects_mismatched_passwords_and_missing_confirmation(): void
    {
        $this->from('/register')
            ->post('/register', [
                'first_name' => 'Ada',
                'last_name' => 'Lovelace',
                'email' => 'ada@example.com',
                'password' => 'a-strong-password',
                'password_confirmation' => 'a-different-password',
            ])
            ->assertRedirect('/register')
            ->assertSessionHasErrors(['password', 'information_confirmed']);

        $this->assertDatabaseMissing('users', ['email' => 'ada@example.com']);
    }

    public function test_registered_user_can_sign_in_without_admin_access(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'a-strong-password',
            'is_admin' => false,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'a-strong-password',
        ])->assertRedirect(route('login.otp'));

        $notification = Notification::sent($user, LoginOtpNotification::class)->first();
        $this->assertInstanceOf(LoginOtpNotification::class, $notification);

        $this->post(route('login.otp.verify'), ['code' => $notification->code])
            ->assertRedirect(route('account.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get('/admin/hero/manage')->assertForbidden();
    }

    public function test_pending_user_cannot_sign_in_before_admin_approval(): void
    {
        $user = User::factory()->create([
            'email' => 'pending@example.com',
            'password' => 'a-strong-password',
            'is_approved' => false,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'a-strong-password',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'Your account is awaiting admin approval.']);

        $this->assertGuest();
    }
}
