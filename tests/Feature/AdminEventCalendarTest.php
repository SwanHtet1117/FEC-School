<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEventCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_the_total_number_of_event_posts(): void
    {
        foreach (range(1, 7) as $number) {
            Post::query()->create([
                'slug' => "dashboard-event-count-{$number}",
                'title' => "Dashboard Event Count {$number}",
                'category' => 'Event',
                'published_at' => '2026-11-18',
                'image_path' => 'fec_building.jpg',
                'alt_text' => 'FEC school building',
                'excerpt' => 'An event used to verify the dashboard count.',
                'body' => 'Dashboard event count test content.',
            ]);
        }
        Post::query()->create([
            'slug' => 'dashboard-news-count',
            'title' => 'Dashboard News Count',
            'category' => 'News',
            'published_at' => '2026-11-18',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'FEC school building',
            'excerpt' => 'A news post not included in the event count.',
            'body' => 'Dashboard news count test content.',
        ]);

        $eventPostCount = Post::query()->where('category', 'Event')->count();

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(
                '<span class="stat-copy"><strong>'.$eventPostCount.'</strong><small>Event Calendar</small></span>',
                false
            );
    }

    public function test_admin_calendar_only_shows_events_for_the_selected_month(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $event = Post::query()->create([
            'slug' => 'calendar-test-event',
            'title' => 'Calendar Test Event',
            'category' => 'Event',
            'published_at' => '2026-11-18',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'FEC school building',
            'excerpt' => 'A calendar test event.',
            'body' => 'An event for the calendar test.',
            'location' => 'School Hall',
        ]);
        Post::query()->create([
            'slug' => 'next-month-test-event',
            'title' => 'Next Month Test Event',
            'category' => 'Event',
            'published_at' => '2026-12-03',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'FEC school building',
            'excerpt' => 'An event outside the selected month.',
            'body' => 'This event should not be shown in November.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.events.calendar', ['month' => '2026-11']))
            ->assertOk()
            ->assertSee('November 2026')
            ->assertSee('Calendar Test Event')
            ->assertSee('School Hall')
            ->assertSee(route('admin.posts.edit', $event))
            ->assertDontSee('Next Month Test Event');
    }

    public function test_event_calendar_requires_admin_access(): void
    {
        $this->get(route('admin.events.calendar'))
            ->assertRedirect(route('login'));
    }
}
