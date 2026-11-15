<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_account_menu_shows_the_user_name_and_account_links(): void
    {
        $user = User::factory()->create(['name' => 'FEC Member']);

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee('FEC Member')
            ->assertSee(route('account.profile.edit'), false)
            ->assertSee(route('account.settings.edit'), false)
            ->assertDontSee(route('admin.dashboard'), false);
    }

    public function test_admin_account_menu_includes_the_dashboard_link(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/')
            ->assertOk()
            ->assertSee(route('admin.dashboard'), false);
    }

    public function test_user_can_update_their_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'phone' => '555-0101',
        ]);

        $this->actingAs($user)
            ->put(route('account.profile.update'), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => '+1 555-0102',
            ])
            ->assertRedirect(route('account.profile.edit'))
            ->assertSessionHas('status', 'Profile updated.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '+1 555-0102',
        ]);
    }

    public function test_user_can_clear_their_phone_number(): void
    {
        $user = User::factory()->create(['phone' => '555-0101']);

        $this->actingAs($user)
            ->put(route('account.profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '',
            ])
            ->assertRedirect(route('account.profile.edit'));

        $this->assertNull($user->fresh()->phone);
    }

    public function test_user_can_change_their_password_with_the_current_password(): void
    {
        $user = User::factory()->create(['password' => 'OldPassword123!']);

        $this->actingAs($user)
            ->put(route('account.settings.update'), [
                'current_password' => 'OldPassword123!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertRedirect(route('account.settings.edit'))
            ->assertSessionHas('status', 'Password updated.');

        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function test_account_pages_require_authentication(): void
    {
        $this->get(route('account.profile.edit'))->assertRedirect(route('login'));
        $this->get(route('account.settings.edit'))->assertRedirect(route('login'));
    }
}
