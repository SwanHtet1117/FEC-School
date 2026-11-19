<?php

namespace Tests\Feature;

use App\Models\SystemActivity;
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
            ->assertSee(route('account.dashboard'), false)
            ->assertSee(route('account.profile.edit'), false)
            ->assertSee(route('account.settings.edit'), false)
            ->assertDontSee('View Requests')
            ->assertDontSee(route('admin.dashboard'), false);
    }

    public function test_admin_account_menu_includes_the_dashboard_link(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/')
            ->assertOk()
            ->assertSee(route('admin.dashboard'), false)
            ->assertSee('View Requests')
            ->assertSee(route('admin.requests.index'), false)
            ->assertDontSee(route('account.dashboard'), false);
    }

    public function test_account_dashboard_shows_only_the_signed_in_users_activities(): void
    {
        $user = User::factory()->create(['name' => 'FEC Member']);
        $otherUser = User::factory()->create();

        SystemActivity::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'Member private activity',
            'details' => 'Own account details',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'UTC',
        ]);
        SystemActivity::create([
            'user_id' => $otherUser->id,
            'user_name' => $otherUser->name,
            'action' => 'Other member private activity',
            'details' => 'Other account details',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'UTC',
        ]);
        SystemActivity::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'Login',
            'details' => 'Signed in',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'UTC',
        ]);
        SystemActivity::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'Logout',
            'details' => 'Signed out',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'UTC',
        ]);

        $this->actingAs($user)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('My Dashboard')
            ->assertSee('Member private activity')
            ->assertSee('Account Activity Snapshot')
            ->assertSee('Other Issues')
            ->assertDontSee('Other member private activity')
            ->assertDontSee('Account shortcuts')
            ->assertDontSee('User Accounts')
            ->assertDontSee('Pending Registrations')
            ->assertViewHas('totalAccountActivityCount', 0)
            ->assertViewHas('photoCount', 0)
            ->assertViewHas('photoPendingCount', 0)
            ->assertViewHas('photoApprovedCount', 0)
            ->assertViewHas('videoCount', 0)
            ->assertViewHas('videoPendingCount', 0)
            ->assertViewHas('videoApprovedCount', 0)
            ->assertViewHas('postCount', 0)
            ->assertViewHas('postPendingCount', 0)
            ->assertViewHas('postApprovedCount', 0)
            ->assertViewHas('heroImageCount', 0)
            ->assertViewHas('heroImagePendingCount', 0)
            ->assertViewHas('heroImageApprovedCount', 0)
            ->assertViewHas('accountActivityDistribution', function (array $distribution): bool {
                $counts = collect($distribution)->pluck('count', 'label');

                return $counts->sum() === 0
                    && ! $counts->has('Account');
            });
    }

    public function test_account_dashboard_shows_today_activity_and_view_all_shows_full_history(): void
    {
        $user = User::factory()->create();
        SystemActivity::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'Today activity',
            'details' => 'Recorded today',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'UTC',
        ])->forceFill(['created_at' => now()])->save();
        SystemActivity::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'Yesterday activity',
            'details' => 'Recorded yesterday',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'UTC',
        ])->forceFill(['created_at' => now()->subDay()])->save();

        $this->actingAs($user)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('Today activity')
            ->assertDontSee('Yesterday activity')
            ->assertSee(route('account.activities.index'), false);

        $this->get(route('account.activities.index'))
            ->assertOk()
            ->assertSee('Today activity')
            ->assertSee('Yesterday activity');
    }

    public function test_account_activity_history_paginates_with_navigation_and_range(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 26) as $number) {
            SystemActivity::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'action' => "Activity {$number}",
                'details' => "Activity details {$number}",
                'status' => 'Success',
                'device' => 'Desktop',
                'timezone' => 'UTC',
            ]);
        }

        $this->actingAs($user)
            ->get(route('account.activities.index'))
            ->assertOk()
            ->assertSee('Showing 1–25 of 26 activities')
            ->assertSee('Page 1 of 2')
            ->assertSee(route('account.activities.index', ['page' => 2]), false)
            ->assertSee('Activity 26')
            ->assertDontSee('Activity 1</span>');

        $this->get(route('account.activities.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Showing 26–26 of 26 activities')
            ->assertSee('Page 2 of 2')
            ->assertSee('Activity 1');
    }

    public function test_account_activity_history_filters_only_the_signed_in_users_records(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        SystemActivity::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'Photo uploaded',
            'details' => 'Annual report photo',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'UTC',
        ]);
        SystemActivity::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'Login',
            'details' => 'Invalid code',
            'status' => 'Failed',
            'device' => 'Mobile',
            'timezone' => 'UTC',
        ]);
        SystemActivity::create([
            'user_id' => $otherUser->id,
            'user_name' => $otherUser->name,
            'action' => 'Login',
            'details' => 'Another user failure',
            'status' => 'Failed',
            'device' => 'Mobile',
            'timezone' => 'UTC',
        ]);

        $this->actingAs($user)
            ->get(route('account.activities.index', ['status' => 'Failed']))
            ->assertOk()
            ->assertSee('Invalid code')
            ->assertDontSee('Annual report photo')
            ->assertDontSee('Another user failure')
            ->assertSee('name="status"', false)
            ->assertViewHas('activities', fn ($activities): bool => $activities->total() === 1);

        $this->get(route('account.activities.index', ['search' => 'Annual report']))
            ->assertOk()
            ->assertSee('Annual report photo')
            ->assertDontSee('Invalid code');
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
        $this->get(route('account.dashboard'))->assertRedirect(route('login'));
        $this->get(route('account.profile.edit'))->assertRedirect(route('login'));
        $this->get(route('account.settings.edit'))->assertRedirect(route('login'));
    }
}
