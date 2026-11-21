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
            ->assertSee('Awaiting approval')
            ->assertSee('Promote to admin');
    }

    public function test_user_deletion_uses_the_confirmation_modal(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->create(['name' => 'Member To Remove']);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('id="adminDeleteDialog"', false)
            ->assertSee('data-delete-type="user account"', false)
            ->assertSee('data-delete-name="Member To Remove"', false)
            ->assertDontSee('onsubmit="return confirm(', false);
    }

    public function test_admin_can_filter_accounts_by_search_status_and_role(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->create([
            'name' => 'Filter Pending Member',
            'email' => 'pending-filter@example.com',
            'is_approved' => false,
        ]);
        User::factory()->create([
            'name' => 'Filter Approved Member',
            'email' => 'approved-filter@example.com',
            'is_approved' => true,
        ]);
        User::factory()->create([
            'name' => 'Other Pending Member',
            'email' => 'other-pending@example.com',
            'is_approved' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index', [
                'search' => 'filter',
                'status' => 'pending',
                'role' => 'user',
            ]))
            ->assertOk()
            ->assertSee('Filter Pending Member')
            ->assertDontSee('Filter Approved Member')
            ->assertDontSee('Other Pending Member')
            ->assertSee('Showing 1–1 of 1 matching accounts')
            ->assertSee('Clear');

        $this->get(route('admin.users.index', ['role' => ['invalid']]))
            ->assertOk()
            ->assertSee('Some filter values are invalid.')
            ->assertSee('Please correct the filters above to view accounts.');
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

    public function test_admin_can_promote_an_approved_user_to_administrator(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create([
            'name' => 'Promoted Member',
            'is_approved' => true,
            'is_admin' => false,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.promote', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status', 'Promoted Member was promoted to administrator.');

        $this->assertTrue($user->fresh()->is_admin);
        $this->assertTrue($user->fresh()->is_approved);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $admin->id,
            'action' => 'User promoted to administrator',
            'details' => 'Promoted Member',
            'status' => 'Success',
        ]);
    }

    public function test_admin_can_disable_and_reenable_a_user_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create([
            'name' => 'Member With Access',
            'is_approved' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.disable', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status', "Member With Access's account was disabled.");

        $this->assertTrue($user->fresh()->is_disabled);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $admin->id,
            'action' => 'User account disabled',
            'details' => 'Member With Access',
            'status' => 'Success',
        ]);

        $this->get(route('admin.users.index', ['status' => 'disabled']))
            ->assertOk()
            ->assertSee('Member With Access')
            ->assertSee('Enable')
            ->assertSee('Disabled');

        $this->actingAs($user->fresh())
            ->get(route('account.dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();

        $this->actingAs($admin)
            ->post(route('admin.users.enable', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status', "Member With Access's account was enabled.");

        $this->assertFalse($user->fresh()->is_disabled);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $admin->id,
            'action' => 'User account enabled',
            'details' => 'Member With Access',
            'status' => 'Success',
        ]);
    }

    public function test_admin_cannot_disable_themselves_or_the_last_active_administrator(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $otherAdmin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.users.disable', $admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('user');

        $this->post(route('admin.users.disable', $otherAdmin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertTrue($otherAdmin->fresh()->is_disabled);

        $this->post(route('admin.users.disable', $admin))
            ->assertSessionHasErrors('user');

        $this->assertFalse($admin->fresh()->is_disabled);
    }

    public function test_admin_cannot_promote_a_pending_user_until_approved(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create([
            'is_approved' => false,
            'is_admin' => false,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.promote', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('user');

        $this->assertFalse($user->fresh()->is_admin);
        $this->assertFalse($user->fresh()->is_approved);
    }

    public function test_non_admin_cannot_promote_a_user(): void
    {
        $user = User::factory()->create(['is_approved' => true]);
        $target = User::factory()->create(['is_approved' => true]);

        $this->actingAs($user)
            ->post(route('admin.users.promote', $target))
            ->assertForbidden();

        $this->assertFalse($target->fresh()->is_admin);
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
