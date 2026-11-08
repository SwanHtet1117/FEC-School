<?php

namespace Tests\Feature;

use App\Models\SystemActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admins_can_open_user_management(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_accounts_with_pending_users_first(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->create([
            'name' => 'Approved Member',
            'is_approved' => true,
        ]);
        User::factory()->create([
            'name' => 'Pending Member',
            'is_approved' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSeeInOrder(['Pending Member', 'Approved Member'])
            ->assertSee('Awaiting approval');
    }

    public function test_admin_can_approve_a_pending_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create([
            'name' => 'New Member',
            'is_approved' => false,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.approve', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status', "New Member's account was approved.");

        $this->assertTrue($user->fresh()->is_approved);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $admin->id,
            'action' => 'User approved',
            'details' => 'New Member',
            'status' => 'Success',
        ]);
    }

    public function test_admin_can_delete_a_regular_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['name' => 'Removed Member']);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status', "Removed Member's account was deleted.");

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $admin->id,
            'action' => 'User deleted',
            'details' => 'Removed Member',
            'status' => 'Success',
        ]);
    }

    public function test_admin_cannot_delete_their_own_account_or_the_last_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('user');

        $member = User::factory()->create();
        $this->delete(route('admin.users.destroy', $member))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
        $this->assertDatabaseHas('users', ['id' => $member->id]);
        $this->assertSame(0, SystemActivity::where('action', 'User deleted')->count());
    }

    public function test_admin_cannot_approve_an_administrator_account_from_user_management(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $pendingAdmin = User::factory()->create([
            'is_admin' => true,
            'is_approved' => false,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.approve', $pendingAdmin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('user');

        $this->assertFalse($pendingAdmin->fresh()->is_approved);
    }
}
