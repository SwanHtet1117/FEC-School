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
            ->assertDontSee('administrator account');
    }
}
