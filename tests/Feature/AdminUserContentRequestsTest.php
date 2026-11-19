<?php

namespace Tests\Feature;

use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserContentRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_user_content_requests_and_their_review_managers(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $submitter = User::factory()->create(['name' => 'Requesting Member']);

        GalleryPhoto::create([
            'title' => 'Member photo request',
            'category' => 'events',
            'image_path' => 'pending-content/photos/member-photo.jpg',
            'alt_text' => 'Member photo request',
            'user_id' => $submitter->id,
            'is_approved' => false,
        ]);
        GalleryVideo::create([
            'title' => 'Member video request',
            'category' => 'events',
            'youtube_id' => 'abcdefghijk',
            'duration' => '2:30',
            'user_id' => $submitter->id,
            'is_approved' => true,
        ]);
        HeroSlide::create([
            'image_path' => 'pending-content/hero-slides/member-hero.jpg',
            'alt_text' => 'Member hero request',
            'sort_order' => 0,
            'is_enabled' => false,
            'user_id' => $submitter->id,
            'is_approved' => false,
            'rejected_at' => now(),
        ]);
        Post::create([
            'slug' => 'member-news-request',
            'title' => 'Member news request',
            'category' => 'News',
            'published_at' => now()->toDateString(),
            'image_path' => 'pending-content/posts/member-news.jpg',
            'alt_text' => 'Member news request',
            'excerpt' => 'A user-submitted news item.',
            'body' => 'Details for the user-submitted news item.',
            'user_id' => $submitter->id,
            'is_approved' => false,
        ]);
        GalleryPhoto::create([
            'title' => 'Admin gallery photo',
            'category' => 'events',
            'image_path' => 'admin-photo.jpg',
            'alt_text' => 'Admin gallery photo',
            'is_approved' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.requests.index'))
            ->assertOk()
            ->assertSee('Member photo request')
            ->assertSee('Member video request')
            ->assertSee('Member hero request')
            ->assertSee('Member news request')
            ->assertSee('Requesting Member')
            ->assertSee('Rejected')
            ->assertDontSee('Admin gallery photo')
            ->assertSee(route('admin.gallery.photos.index'), false)
            ->assertSee(route('admin.gallery.videos.index'), false)
            ->assertSee(route('admin.hero.manage'), false)
            ->assertSee(route('admin.posts.index'), false)
            ->assertViewHas('requests', fn ($requests): bool => $requests->total() === 4);
    }

    public function test_user_requests_page_can_filter_by_type_status_and_search(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $submitter = User::factory()->create();

        GalleryPhoto::create([
            'title' => 'Science fair submission',
            'category' => 'events',
            'image_path' => 'pending-content/photos/science-fair.jpg',
            'alt_text' => 'Science fair submission',
            'user_id' => $submitter->id,
            'is_approved' => false,
        ]);
        GalleryVideo::create([
            'title' => 'Student concert video',
            'category' => 'events',
            'youtube_id' => 'abcdefghijk',
            'duration' => '2:30',
            'user_id' => $submitter->id,
            'is_approved' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.requests.index', [
                'type' => 'photo',
                'status' => 'pending',
                'search' => 'Science fair',
            ]))
            ->assertOk()
            ->assertSee('Science fair submission')
            ->assertDontSee('Student concert video')
            ->assertViewHas('requests', fn ($requests): bool => $requests->total() === 1);
    }
}
