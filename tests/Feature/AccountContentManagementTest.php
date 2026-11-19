<?php

namespace Tests\Feature;

use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_actionable_counts_for_pending_user_content(): void
    {
        $owner = User::factory()->create();
        HeroSlide::query()->create([
            'image_path' => 'pending-content/hero-slides/hero.png',
            'alt_text' => 'Pending dashboard hero',
            'sort_order' => 10,
            'is_enabled' => false,
            'user_id' => $owner->id,
            'is_approved' => false,
        ]);
        HeroSlide::query()->create([
            'image_path' => 'pending-content/hero-slides/rejected.png',
            'alt_text' => 'Rejected dashboard hero',
            'sort_order' => 11,
            'is_enabled' => false,
            'user_id' => $owner->id,
            'is_approved' => false,
            'rejected_at' => now(),
        ]);
        GalleryPhoto::query()->create([
            'title' => 'Pending dashboard photo',
            'category' => 'events',
            'image_path' => 'pending-content/photos/photo.png',
            'alt_text' => 'A pending school photo',
            'user_id' => $owner->id,
            'is_approved' => false,
        ]);
        GalleryVideo::query()->create([
            'title' => 'Pending dashboard video',
            'category' => 'events',
            'youtube_id' => 'AbCdEf12345',
            'duration' => '8:45',
            'user_id' => $owner->id,
            'is_approved' => false,
        ]);
        Post::query()->create([
            'slug' => 'pending-dashboard-news',
            'title' => 'Pending dashboard news',
            'category' => 'News',
            'published_at' => '2027-01-15',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'School news',
            'excerpt' => 'A pending news post.',
            'body' => 'Pending post content.',
            'user_id' => $owner->id,
            'is_approved' => false,
        ]);
        Post::query()->create([
            'slug' => 'pending-dashboard-announcement',
            'title' => 'Pending dashboard announcement',
            'category' => 'Announcement',
            'published_at' => '2027-01-16',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'School announcement',
            'excerpt' => 'An announcement.',
            'body' => 'Announcements are not user submissions.',
            'user_id' => $owner->id,
            'is_approved' => false,
        ]);
        HeroSlide::query()->create([
            'image_path' => 'hero-slides/approved.png',
            'alt_text' => 'Approved dashboard hero',
            'sort_order' => 12,
            'is_enabled' => true,
            'user_id' => $owner->id,
            'is_approved' => true,
        ]);
        GalleryPhoto::query()->create([
            'title' => 'Approved dashboard photo',
            'category' => 'events',
            'image_path' => 'gallery-photos/approved.png',
            'alt_text' => 'An approved school photo',
            'user_id' => $owner->id,
            'is_approved' => true,
        ]);
        GalleryVideo::query()->create([
            'title' => 'Approved dashboard video',
            'category' => 'events',
            'youtube_id' => 'AbCdEf12346',
            'duration' => '8:45',
            'user_id' => $owner->id,
            'is_approved' => true,
        ]);
        Post::query()->create([
            'slug' => 'approved-dashboard-event',
            'title' => 'Approved dashboard event',
            'category' => 'Event',
            'published_at' => '2027-01-17',
            'image_path' => 'posts/approved.png',
            'alt_text' => 'Approved school event',
            'excerpt' => 'An approved event.',
            'body' => 'Approved event content.',
            'user_id' => $owner->id,
            'is_approved' => true,
        ]);

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Pending User Submissions')
            ->assertSee('1 pending photo submission')
            ->assertSee('1 pending video submission')
            ->assertSee('1 pending News/Event post')
            ->assertSee('1 pending hero image submission')
            ->assertViewHas('pendingPhotoApprovalCount', 1)
            ->assertViewHas('approvedPhotoCount', 1)
            ->assertViewHas('pendingVideoApprovalCount', 1)
            ->assertViewHas('approvedVideoCount', 1)
            ->assertViewHas('pendingPostApprovalCount', 1)
            ->assertViewHas('approvedPostCount', 1)
            ->assertViewHas('pendingHeroApprovalCount', 1)
            ->assertViewHas('approvedHeroCount', 1)
            ->assertSee('Pending photo submissions')
            ->assertSee('Pending video submissions')
            ->assertSee('Pending News/Event posts')
            ->assertSee('Pending hero image submissions')
            ->assertSee('href="'.route('admin.gallery.photos.index').'"', false)
            ->assertSee('href="'.route('admin.gallery.videos.index').'"', false)
            ->assertSee('href="'.route('admin.posts.index').'"', false)
            ->assertSee('href="'.route('admin.hero.manage').'"', false)
            ->assertDontSee('Pending dashboard announcement');
    }

    public function test_account_dashboard_uses_role_appropriate_hero_management_link(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('href="'.route('account.content.hero').'"', false)
            ->assertSee('Submit images for admin review');

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('href="'.route('admin.hero.manage').'"', false)
            ->assertSee('Manage the live homepage slideshow');
    }

    public function test_account_dashboard_shows_recent_status_updates_for_only_its_own_submissions(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        GalleryPhoto::query()->create([
            'title' => 'My pending photo',
            'category' => 'events',
            'image_path' => 'pending-content/photos/my-photo.png',
            'alt_text' => 'My pending photo description',
            'user_id' => $owner->id,
            'is_approved' => false,
        ]);
        GalleryVideo::query()->create([
            'title' => 'My approved video',
            'category' => 'events',
            'youtube_id' => 'AbCdEf12345',
            'duration' => '8:45',
            'user_id' => $owner->id,
            'is_approved' => true,
        ]);
        Post::query()->create([
            'slug' => 'my-approved-update',
            'title' => 'My approved News update',
            'category' => 'News',
            'published_at' => '2027-01-15',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'School news',
            'excerpt' => 'A news post.',
            'body' => 'News content.',
            'user_id' => $owner->id,
            'is_approved' => true,
        ]);
        HeroSlide::query()->create([
            'image_path' => 'pending-content/hero-slides/my-hero.png',
            'alt_text' => 'My pending hero',
            'sort_order' => 10,
            'is_enabled' => false,
            'user_id' => $owner->id,
            'is_approved' => false,
        ]);
        Post::query()->create([
            'slug' => 'another-users-update',
            'title' => 'Another user submission',
            'category' => 'News',
            'published_at' => '2027-01-16',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'Another user news',
            'excerpt' => 'A private news post.',
            'body' => 'Private news content.',
            'user_id' => $otherUser->id,
            'is_approved' => false,
        ]);

        $this->actingAs($owner)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('Submission updates')
            ->assertSee('My pending photo')
            ->assertSee('My approved video')
            ->assertSee('My approved News update')
            ->assertSee('My pending hero')
            ->assertSee('Awaiting review')
            ->assertSee('Approved')
            ->assertDontSee('Pending photo submissions')
            ->assertDontSee('Another user submission');
    }

    public function test_user_hero_image_submissions_are_private_until_admin_approval(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $owner = User::factory()->create();

        $this->actingAs($owner)
            ->post(route('account.content.hero.store'), [
                'image' => $this->testImage(),
                'alt_text' => 'Member hero image',
            ])
            ->assertRedirect(route('account.content.hero'))
            ->assertSessionHas('status', 'Your hero image was submitted for admin review.');

        $slide = HeroSlide::query()->where('alt_text', 'Member hero image')->firstOrFail();
        $this->assertFalse($slide->is_approved);
        $this->assertFalse($slide->is_enabled);
        Storage::disk('local')->assertExists($slide->image_path);
        $this->get('/')->assertDontSee($slide->image_url, false);
        $this->actingAs($owner)->get($slide->image_url)->assertOk();
        $this->put(route('account.content.hero.update', $slide), [
            'alt_text' => 'Updated member hero image',
        ])->assertRedirect(route('account.content.hero'))
            ->assertSessionHas('status', 'Your changes were submitted for admin review.');
        $slide->refresh();
        $this->assertSame('Updated member hero image', $slide->alt_text);

        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)
            ->get($slide->image_url)
            ->assertNotFound();
        $this->put(route('account.content.hero.update', $slide), [
            'alt_text' => 'Not the owner',
        ])->assertNotFound();

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('admin.hero.manage'))
            ->assertOk()
            ->assertSee('Member hero image')
            ->assertSee('Approve');

        $this->post(route('admin.hero.approve', $slide))
            ->assertRedirect(route('admin.hero.manage'))
            ->assertSessionHas('status', 'Hero image approved and published.');

        $slide->refresh();
        $this->assertTrue($slide->is_approved);
        $this->assertTrue($slide->is_enabled);
        Storage::disk('local')->assertMissing('pending-content/hero-slides/'.basename($slide->image_path));
        Storage::disk('public')->assertExists($slide->image_path);
        $this->get('/')->assertSee($slide->image_url, false);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $owner->id,
            'action' => 'Hero image approved by admin',
            'details' => 'Updated member hero image',
        ]);
        $this->actingAs($owner)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('Approved')
            ->assertSee('Hero image approved by admin')
            ->assertSee('aria-label="Notifications, 1 submission updates"', false)
            ->assertSee('Hero image approved');

        $this->actingAs($owner)
            ->put(route('account.content.hero.update', $slide), [
                'alt_text' => 'Edited after approval',
            ])->assertNotFound();
    }

    public function test_admin_rejection_is_visible_to_submitter_and_can_be_resubmitted(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $path = 'pending-content/hero-slides/rejected-hero.png';
        Storage::disk('local')->put($path, 'pending hero image');
        $slide = HeroSlide::query()->create([
            'image_path' => $path,
            'alt_text' => 'Rejected hero image',
            'sort_order' => 10,
            'is_enabled' => false,
            'user_id' => $owner->id,
            'is_approved' => false,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.hero.reject', $slide))
            ->assertRedirect(route('admin.hero.manage'))
            ->assertSessionHas('status', 'Hero image rejected.');

        $slide->refresh();
        $this->assertNotNull($slide->rejected_at);
        $this->assertFalse($slide->is_approved);
        $this->assertFalse($slide->is_enabled);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $owner->id,
            'action' => 'Hero image rejected by admin',
            'details' => 'Rejected hero image',
        ]);

        $this->actingAs($owner)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('Rejected - revise and resubmit')
            ->assertSee('Hero image rejected by admin')
            ->assertSee('aria-label="Notifications, 1 submission updates"', false)
            ->assertSee('Hero image rejected');
        $this->get(route('account.content.hero'))
            ->assertOk()
            ->assertSee('Rejected - edit and resubmit');

        $this->put(route('account.content.hero.update', $slide), [
            'alt_text' => 'Revised hero image',
        ])->assertRedirect(route('account.content.hero'))
            ->assertSessionHas('status', 'Your changes were submitted for admin review.');

        $slide->refresh();
        $this->assertNull($slide->rejected_at);
        $this->assertFalse($slide->is_approved);
        $this->assertDatabaseHas('system_activities', [
            'user_id' => $owner->id,
            'action' => 'Hero image submission updated',
            'details' => 'Revised hero image',
        ]);
    }

    public function test_photo_submissions_are_private_until_an_admin_approves_them(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $owner = User::factory()->create();

        $this->actingAs($owner)->post(route('account.content.photos.store'), [
            'image' => $this->testImage(),
            'title' => 'Member Photo',
            'category' => 'events',
            'alt_text' => 'Members at a school event',
        ])->assertRedirect(route('account.content.photos'))
            ->assertSessionHas('status', 'Your photo was submitted for admin review.');

        $photo = GalleryPhoto::query()->where('title', 'Member Photo')->firstOrFail();
        $this->assertFalse($photo->is_approved);
        Storage::disk('local')->assertExists($photo->image_path);
        $this->postJson(route('gallery.photos.view', $photo))->assertNotFound();
        $this->get('/gallery/photo')->assertDontSee('Member Photo');

        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)
            ->get(route('account.content.photos.preview', $photo))
            ->assertNotFound();
        $this->put(route('account.content.photos.update', $photo), [
            'title' => 'Changed by another user',
            'category' => 'events',
            'alt_text' => 'Not the owner',
        ])->assertNotFound();

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->post(route('admin.gallery.photos.approve', $photo))
            ->assertRedirect(route('admin.gallery.photos.index'))
            ->assertSessionHas('status', 'Photo approved and published.');

        $photo->refresh();
        $this->assertTrue($photo->is_approved);
        Storage::disk('local')->assertMissing('pending-content/photos/'.basename($photo->image_path));
        Storage::disk('public')->assertExists($photo->image_path);
        $this->get('/gallery/photo')->assertSee('Member Photo');
        $this->actingAs($owner)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('aria-label="Notifications, 1 submission updates"', false)
            ->assertSee('Photo approved')
            ->assertSee('Member Photo');
    }

    public function test_video_and_news_event_submissions_require_admin_approval(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner)->post(route('account.content.videos.store'), [
            'title' => 'Member Video',
            'category' => 'events',
            'youtube_id' => 'AbCdEf12345',
            'duration' => '8:45',
        ])->assertRedirect(route('account.content.videos'));

        $video = GalleryVideo::query()->where('title', 'Member Video')->firstOrFail();
        $this->assertFalse($video->is_approved);
        $this->get('/gallery/video')->assertDontSee('Member Video');

        $this->post(route('account.content.posts.store'), [
            'title' => 'Member News',
            'category' => 'News',
            'published_at' => '2027-01-15',
            'excerpt' => 'A member-submitted news story.',
            'body' => 'Details of the story.',
            'alt_text' => 'School news',
            'location' => '',
        ])->assertRedirect(route('account.content.posts'));

        $post = Post::query()->where('title', 'Member News')->firstOrFail();
        $this->assertFalse($post->is_approved);
        $this->get(route('news-events.all', ['category' => 'News']))->assertDontSee('Member News');
        $this->get(route('news-events.posts.show', $post->slug))->assertNotFound();

        $this->from(route('account.content.posts'))
            ->post(route('account.content.posts.store'), [
                'title' => 'Member Announcement',
                'category' => 'Announcement',
                'published_at' => '2027-01-15',
                'excerpt' => 'Not allowed on the user submission form.',
                'body' => 'Announcement content.',
                'alt_text' => 'School update',
            ])
            ->assertRedirect(route('account.content.posts'))
            ->assertSessionHasErrors('category');

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->post(route('admin.gallery.videos.approve', $video))
            ->assertRedirect(route('admin.gallery.videos.index'));
        $this->post(route('admin.posts.approve', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->get('/gallery/video')->assertSee('Member Video');
        $this->get(route('news-events.all', ['category' => 'News']))->assertSee('Member News');
        $this->get(route('news-events.posts.show', $post->slug))->assertOk();
        $this->actingAs($owner)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('aria-label="Notifications, 2 submission updates"', false)
            ->assertSee('Video approved')
            ->assertSee('Post approved');
    }

    private function testImage(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'member-photo.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/r4sAAAAASUVORK5CYII=')
        );
    }
}
