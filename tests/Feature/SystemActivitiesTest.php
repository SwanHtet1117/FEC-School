<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\SystemActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemActivitiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_logout_attempts_are_recorded_with_the_request_device(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'is_admin' => true,
        ]);

        $this->withHeader('User-Agent', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) Mobile')
            ->post('/login', [
                'email' => $user->email,
                'password' => 'incorrect-password',
            ])
            ->assertRedirect('/login');

        $this->assertDatabaseHas('system_activities', [
            'user_name' => 'Unknown User',
            'action' => 'Login',
            'details' => 'Invalid credentials',
            'status' => 'Failed',
            'device' => 'Mobile',
        ]);

        $this->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)')
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
                'timezone' => 'Asia/Yangon',
            ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('system_activities', [
            'user_id' => $user->id,
            'user_name' => 'Admin User',
            'action' => 'Login',
            'details' => 'Signed in',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'Asia/Yangon',
        ]);

        $this->post('/logout')->assertRedirect(route('login'));

        $this->assertDatabaseHas('system_activities', [
            'user_id' => $user->id,
            'action' => 'Logout',
            'details' => 'Signed out',
            'status' => 'Success',
            'timezone' => 'Asia/Yangon',
        ]);
    }

    public function test_dashboard_displays_activity_time_in_the_recorded_device_timezone(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->travelTo(Carbon::parse('2026-11-08 12:00:00', 'UTC'));

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
            'timezone' => 'Asia/Yangon',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('06:30 PM')
            ->assertSee('Nov 08, 2026 · 06:30 PM')
            ->assertSee('Asia/Yangon');
    }

    public function test_successful_hero_changes_are_recorded(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $slide = HeroSlide::ordered()->skip(1)->firstOrFail();

        $this->actingAs($admin)
            ->post("/admin/hero/manage/{$slide->id}/toggle")
            ->assertRedirect('/admin/hero/manage');

        $this->assertDatabaseHas('system_activities', [
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'action' => 'Hero image disabled',
            'details' => $slide->alt_text,
            'status' => 'Success',
        ]);
    }

    public function test_dashboard_displays_recent_recorded_activities(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        SystemActivity::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'action' => 'Hero image updated',
            'details' => 'Updated campus entrance image',
            'status' => 'Success',
            'device' => 'Desktop',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Updated campus entrance image')
            ->assertSee('Hero image updated')
            ->assertSee('activity-action--updated', false)
            ->assertDontSee('administrator account');
    }

    public function test_activity_actions_use_distinct_color_categories_on_both_pages(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $actions = [
            'Login' => 'authentication',
            'Career opportunity added' => 'added',
            'Hero image updated' => 'updated',
            'User deleted' => 'deleted',
            'User approved' => 'approved',
            'Hero image disabled' => 'availability',
            'Content reviewed' => 'other',
        ];

        foreach ($actions as $action => $color) {
            SystemActivity::create([
                'user_name' => $admin->name,
                'action' => $action,
                'details' => $action,
                'status' => 'Success',
                'device' => 'Desktop',
                'timezone' => 'UTC',
            ]);
        }

        foreach ([route('admin.dashboard'), route('admin.activities.index')] as $url) {
            $response = $this->actingAs($admin)->get($url)->assertOk();

            foreach ($actions as $color) {
                $response->assertSee("activity-action--{$color}", false);
            }
        }
    }

    public function test_admin_can_view_all_system_activities_across_pages(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        foreach (range(1, 26) as $number) {
            SystemActivity::create([
                'user_name' => 'Admin User',
                'action' => 'Content updated',
                'details' => sprintf('Activity entry %03d', $number),
                'status' => 'Success',
                'device' => 'Desktop',
                'timezone' => 'UTC',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.activities.index'))
            ->assertOk()
            ->assertSee('Activity Log')
            ->assertSee('Activity entry 026')
            ->assertSee('activity-action--updated', false)
            ->assertDontSee('Activity entry 001')
            ->assertSee('Page 1 of 2');

        $this->get(route('admin.activities.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Activity entry 001')
            ->assertSee('Page 2 of 2');
    }

    public function test_activity_filters_can_be_combined_and_invalid_values_are_reported(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        SystemActivity::create([
            'user_name' => 'Admin User',
            'action' => 'Login',
            'details' => 'Signed in successfully',
            'status' => 'Success',
            'device' => 'Desktop',
            'timezone' => 'UTC',
        ]);
        SystemActivity::create([
            'user_name' => 'Admin User',
            'action' => 'Login',
            'details' => 'Invalid credentials',
            'status' => 'Failed',
            'device' => 'Mobile',
            'timezone' => 'UTC',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.activities.index', [
                'search' => 'credentials',
                'status' => 'Failed',
                'device' => 'Mobile',
            ]))
            ->assertOk()
            ->assertSee('Invalid credentials')
            ->assertDontSee('Signed in successfully');

        $this->get(route('admin.activities.index', ['status' => 'Unknown']))
            ->assertOk()
            ->assertSee('Some filter values are invalid.')
            ->assertSee('Please correct the filters above');
    }
}
