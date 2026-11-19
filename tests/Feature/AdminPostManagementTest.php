<?php

namespace Tests\Feature;

use App\Models\CareerOpportunity;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPostManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_the_aggregate_content_count(): void
    {
        Post::query()->create([
            'slug' => 'dashboard-post-count-test',
            'title' => 'Dashboard Post Count Test',
            'category' => 'News',
            'published_at' => '2026-11-15',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'FEC school building',
            'excerpt' => 'A post used to verify the dashboard count.',
            'body' => 'Dashboard count test content.',
        ]);

        $totalContentCount = HeroSlide::query()->count()
            + Post::query()->count()
            + GalleryPhoto::query()->count()
            + GalleryVideo::query()->count()
            + Faq::query()->count()
            + ContactMessage::query()->count()
            + CareerOpportunity::query()->count()
            + Testimonial::query()->count();

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(
                '<span class="stat-copy"><strong>'.$totalContentCount.'</strong><small>Total Posts</small></span>',
                false
            )
            ->assertSee('Admin Content Distribution')
            ->assertSee('conic-gradient(', false)
            ->assertSee('content-distribution-chart-center')
            ->assertSee('<span>Total</span>', false)
            ->assertViewHas('totalContentCount', $totalContentCount)
            ->assertViewHas('contentDistribution', function (array $distribution) use ($totalContentCount): bool {
                $counts = collect($distribution)->pluck('count', 'label');

                return $counts->all() === [
                    'Hero Images' => HeroSlide::query()->count(),
                    'News, Announcements & Events' => Post::query()->count(),
                    'Photo Gallery' => GalleryPhoto::query()->count(),
                    'Video Gallery' => GalleryVideo::query()->count(),
                    'FAQs' => Faq::query()->count(),
                    'Contact Messages' => ContactMessage::query()->count(),
                    'Career Opportunities' => CareerOpportunity::query()->count(),
                    'Testimonials' => Testimonial::query()->count(),
                ] && $counts->sum() === $totalContentCount;
            });
    }

    public function test_admin_can_create_a_post_and_publish_it_on_the_public_pages(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'School Open Day',
                'category' => 'Event',
                'published_at' => '2026-12-10',
                'excerpt' => 'Visit the school and meet our teachers.',
                'body' => 'Families are welcome to visit the campus.',
                'image' => $this->testImage(),
                'alt_text' => 'Families visiting the school',
                'location' => 'FEC Campus',
                'department' => '',
            ])
            ->assertRedirect(route('admin.posts.index'))
            ->assertSessionHas('status', 'Post published.');

        $post = Post::query()->where('slug', 'school-open-day-2')->firstOrFail();
        $this->assertSame('school-open-day-2', $post->slug);
        Storage::disk('public')->assertExists($post->image_path);

        $this->get(route('news-events.index'))
            ->assertOk()
            ->assertSee('School Open Day')
            ->assertSee('Visit the school and meet our teachers.');
        $this->get(route('news-events.all', ['category' => 'Event']))
            ->assertOk()
            ->assertSee('School Open Day');
        $this->get(route('news-events.posts.show', $post->slug))
            ->assertOk()
            ->assertSee('FEC Campus');
    }

    public function test_homepage_shows_the_latest_news_and_events_but_not_announcements(): void
    {
        foreach ([
            ['slug' => 'homepage-news', 'title' => 'Homepage News', 'category' => 'News', 'published_at' => '2027-01-03'],
            ['slug' => 'homepage-event', 'title' => 'Homepage Event', 'category' => 'Event', 'published_at' => '2027-01-02'],
            ['slug' => 'homepage-older-news', 'title' => 'Homepage Older News', 'category' => 'News', 'published_at' => '2027-01-01'],
            ['slug' => 'homepage-announcement', 'title' => 'Homepage Announcement', 'category' => 'Announcement', 'published_at' => '2027-01-04'],
        ] as $post) {
            Post::query()->create($post + [
                'image_path' => 'fec_building.jpg',
                'alt_text' => 'FEC school building',
                'excerpt' => 'A homepage post.',
                'body' => 'Homepage post content.',
            ]);
        }

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['Homepage News', 'Homepage Event', 'Homepage Older News'])
            ->assertDontSee('Homepage Announcement')
            ->assertSee('alt="FEC school building"', false);
    }

    public function test_admin_can_update_and_delete_a_post(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $post = Post::query()->where('slug', 'school-open-day')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.posts.update', $post), [
                'title' => 'Campus Open House',
                'category' => 'Event',
                'published_at' => '2026-12-11',
                'excerpt' => 'Meet our school community.',
                'body' => 'Visit the campus and meet the team.',
                'alt_text' => 'The FEC campus',
                'location' => 'FEC Campus',
                'department' => '',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post->refresh();
        $this->assertSame('campus-open-house', $post->slug);
        $this->get(route('news-events.posts.show', $post->slug))
            ->assertOk()
            ->assertSee('Campus Open House');

        $this->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->get(route('news-events.posts.show', 'campus-open-house'))->assertNotFound();
    }

    public function test_admin_can_edit_a_user_news_submission_without_changing_pending_status(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $member = User::factory()->create([
            'name' => 'Member Submitter',
            'email' => 'member@example.com',
        ]);
        $post = Post::query()->create([
            'slug' => 'member-submitted-news',
            'title' => 'Member Submitted News',
            'category' => 'News',
            'published_at' => '2027-01-15',
            'image_path' => 'pending-content/posts/member-news.png',
            'alt_text' => 'Member news image',
            'excerpt' => 'Original member summary.',
            'body' => 'Original member content.',
            'location' => null,
            'department' => null,
            'user_id' => $member->id,
            'is_approved' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.posts.edit', $post))
            ->assertOk()
            ->assertSee('Member Submitter')
            ->assertSee('member@example.com')
            ->assertSee('Awaiting review');

        $this->put(route('admin.posts.update', $post), [
            'title' => 'Admin Edited Member News',
            'category' => 'Event',
            'published_at' => '2027-01-16',
            'excerpt' => 'Admin corrected summary.',
            'body' => 'Admin corrected content.',
            'alt_text' => 'Corrected member image',
            'location' => 'FEC Hall',
            'department' => '',
        ])->assertRedirect(route('admin.posts.index'));

        $post->refresh();
        $this->assertSame('Admin Edited Member News', $post->title);
        $this->assertSame('Event', $post->category);
        $this->assertFalse($post->is_approved);
    }

    public function test_news_events_page_shows_only_the_three_latest_posts_in_each_category(): void
    {
        foreach (['News', 'Announcement', 'Event'] as $category) {
            foreach (range(1, 4) as $number) {
                Post::query()->create([
                    'slug' => strtolower($category).'-latest-test-'.$number,
                    'title' => "{$category} Latest Test {$number}",
                    'category' => $category,
                    'published_at' => "2027-01-0{$number}",
                    'image_path' => 'fec_building.jpg',
                    'alt_text' => 'FEC school building',
                    'excerpt' => 'A test post.',
                    'body' => 'Post content for the latest-post limit test.',
                ]);
            }
        }

        $response = $this->get(route('news-events.index'))
            ->assertOk()
            ->assertViewHas('news', fn ($posts): bool => $posts->take(3)->pluck('title')->all() === [
                'News Latest Test 4',
                'News Latest Test 3',
                'News Latest Test 2',
            ])
            ->assertViewHas('events', fn ($posts): bool => $posts->take(3)->pluck('title')->all() === [
                'Event Latest Test 4',
                'Event Latest Test 3',
                'Event Latest Test 2',
            ]);

        foreach (['News', 'Announcement', 'Event'] as $category) {
            foreach ([2, 3, 4] as $number) {
                $response->assertSee("{$category} Latest Test {$number}");
            }
            $response->assertDontSee("{$category} Latest Test 1");
        }

        $this->get(route('news-events.all', ['category' => 'News']))
            ->assertOk()
            ->assertSeeInOrder([
                'News Latest Test 4',
                'News Latest Test 3',
                'News Latest Test 2',
                'News Latest Test 1',
            ]);
    }

    public function test_news_events_page_displays_the_selected_month_event_calendar(): void
    {
        $januaryEvent = Post::query()->create([
            'slug' => 'january-calendar-event',
            'title' => 'January Calendar Event',
            'category' => 'Event',
            'published_at' => '2027-01-15',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'FEC school building',
            'excerpt' => 'A January calendar event.',
            'body' => 'An event shown in the January calendar.',
        ]);
        Post::query()->create([
            'slug' => 'february-calendar-event',
            'title' => 'February Calendar Event',
            'category' => 'Event',
            'published_at' => '2027-02-15',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'FEC school building',
            'excerpt' => 'A February calendar event.',
            'body' => 'An event outside the selected month.',
        ]);

        $this->get(route('news-events.index', ['month' => '2027-01']))
            ->assertOk()
            ->assertSee('January 2027')
            ->assertSee('January Calendar Event')
            ->assertViewHas('calendarMonth', fn ($month): bool => $month->format('Y-m') === '2027-01')
            ->assertViewHas('calendarEventCount', 1)
            ->assertViewHas('calendarDays', fn ($days): bool => $days->contains(
                fn (array $day): bool => $day['date']->toDateString() === '2027-01-15'
                    && $day['events']->contains('id', $januaryEvent->id)
            ));
    }

    public function test_post_management_requires_an_admin_and_valid_categories(): void
    {
        $this->get(route('admin.posts.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->from(route('admin.posts.index'))
            ->post(route('admin.posts.store'), [
                'title' => 'Invalid Category Post',
                'category' => 'Other',
                'published_at' => '2026-12-10',
                'excerpt' => 'A sample excerpt.',
                'body' => 'A sample post.',
                'alt_text' => 'A sample image',
            ])
            ->assertRedirect(route('admin.posts.index'))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseMissing('posts', ['title' => 'Invalid Category Post']);
    }

    private function test_image(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'school-event.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/r4sAAAAASUVORK5CYII=')
        );
    }
}
