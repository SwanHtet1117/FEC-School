<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

    public function test_visitor_can_register_a_regular_account_and_is_signed_in(): void
    {
        $this->post('/register', [
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'phone' => '+95 123456789',
            'password' => 'a-strong-password',
            'password_confirmation' => 'a-strong-password',
            'information_confirmed' => '1',
            'is_admin' => '1',
        ])->assertRedirect(route('home'))
            ->assertSessionHas('status', 'Your account has been created.');

        $user = User::where('email', 'ada@example.com')->firstOrFail();

        $this->assertSame('Ada Lovelace', $user->name);
        $this->assertSame('+95 123456789', $user->phone);
        $this->assertFalse($user->is_admin);
        $this->assertTrue(Hash::check('a-strong-password', $user->password));
        $this->assertAuthenticatedAs($user);
        $this->get(route('home'))->assertSee('Sign out');
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
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'a-strong-password',
            'is_admin' => false,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'a-strong-password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
        $this->get('/admin/hero/manage')->assertForbidden();
    }
}
