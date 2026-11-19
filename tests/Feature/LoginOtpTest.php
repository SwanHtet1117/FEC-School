<?php

namespace Tests\Feature;

use App\Models\LoginOtp;
use App\Models\User;
use App\Notifications\LoginOtpNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LoginOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_password_reset_screens_show_the_active_code_countdown(): void
    {
        Notification::fake();
        $this->travelTo(now());
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_approved' => true,
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('login.otp'));

        $this->get(route('login.otp'))
            ->assertOk()
            ->assertSee('data-otp-countdown', false)
            ->assertSee('data-otp-countdown-seconds="600"', false)
            ->assertSee('10:00');

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect(route('password.otp'));

        $this->get(route('password.otp'))
            ->assertOk()
            ->assertSee('data-otp-countdown', false)
            ->assertSee('data-otp-countdown-seconds="600"', false)
            ->assertSee('10:00');
    }

    public function test_password_login_sends_a_single_use_email_code_before_authenticating(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_approved' => true,
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
            'remember' => '1',
            'timezone' => 'Asia/Yangon',
        ])->assertRedirect(route('login.otp'))
            ->assertSessionHas('status', 'A one-time sign-in code has been sent to your email.');

        $this->assertGuest();
        $challenge = LoginOtp::query()->where('user_id', $user->id)->firstOrFail();
        $this->assertNull($challenge->consumed_at);
        $this->assertSame(0, $challenge->attempts);

        $notification = Notification::sent($user, LoginOtpNotification::class)->first();
        $this->assertInstanceOf(LoginOtpNotification::class, $notification);
        $this->assertSame(6, strlen($notification->code));

        $this->get(route('login.otp'))
            ->assertOk()
            ->assertSee('Enter Your One-Time Code');

        $this->post(route('login.otp.verify'), ['code' => $notification->code])
            ->assertRedirect(route('account.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($challenge->fresh()->consumed_at);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $user->id,
            'action' => 'Login',
            'details' => 'Signed in using email OTP',
            'timezone' => 'Asia/Yangon',
        ]);
    }

    public function test_incorrect_codes_are_limited_and_the_challenge_is_consumed(): void
    {
        Notification::fake();
        $user = User::factory()->create(['is_approved' => true]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('login.otp'));

        $notification = Notification::sent($user, LoginOtpNotification::class)->first();
        $this->assertInstanceOf(LoginOtpNotification::class, $notification);
        $incorrectCode = $notification->code === '000000' ? '000001' : '000000';
        $this->get(route('login.otp'))->assertOk();

        for ($attempt = 1; $attempt < 5; $attempt++) {
            $this->post(route('login.otp.verify'), ['code' => $incorrectCode])
                ->assertSessionHasErrors('code');

            if ($attempt === 1) {
                $this->get(route('login.otp'))
                    ->assertOk()
                    ->assertSee('alert alert-danger auth-login-error', false);
            }
        }

        $this->post(route('login.otp.verify'), ['code' => $incorrectCode])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertSame(5, LoginOtp::query()->where('user_id', $user->id)->value('attempts'));
        $this->assertNotNull(LoginOtp::query()->where('user_id', $user->id)->value('consumed_at'));
    }

    public function test_failed_login_attempts_are_limited_per_email_across_ip_addresses(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'email' => 'burst-test@example.com',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        foreach (range(1, 4) as $attempt) {
            $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.'.$attempt])
                ->post(route('login.store'), [
                    'email' => $user->email,
                    'password' => 'wrong-password-'.$attempt,
                ])
                ->assertSessionHasErrors('email');
        }

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.5'])
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('login.otp'));

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.6'])
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'wrong-password-5',
            ])
            ->assertSessionHasErrors('email');

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.7'])
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'wrong-password-6',
            ])
            ->assertTooManyRequests();
    }

    public function test_expired_code_can_be_resent(): void
    {
        Notification::fake();
        $user = User::factory()->create(['is_approved' => true]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('login.otp'));

        $firstNotification = Notification::sent($user, LoginOtpNotification::class)->first();
        $this->assertInstanceOf(LoginOtpNotification::class, $firstNotification);
        $oldChallenge = LoginOtp::query()->where('user_id', $user->id)->firstOrFail();
        $oldChallenge->update(['expires_at' => now()->subMinute()]);

        $this->get(route('login.otp'))
            ->assertOk()
            ->assertSee('This code has expired.');

        $this->post(route('login.otp.resend'))
            ->assertRedirect(route('login.otp'))
            ->assertSessionHas('status', 'A new one-time sign-in code has been sent to your email.');

        $newChallenge = LoginOtp::query()->where('user_id', $user->id)->firstOrFail();
        $this->assertNotSame($oldChallenge->id, $newChallenge->id);
        $this->assertTrue($newChallenge->expires_at->isFuture());
        $this->assertSame(0, $newChallenge->attempts);
        $this->assertCount(2, Notification::sent($user, LoginOtpNotification::class));
    }
}
