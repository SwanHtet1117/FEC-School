<?php

namespace Tests\Feature;

use App\Models\GalleryVideo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryVideoManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_an_admin_can_open_video_management(): void
    {
        $this->get(route('admin.gallery.videos.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.gallery.videos.index'))
            ->assertForbidden();
    }

    public function test_existing_videos_are_displayed_on_the_public_gallery(): void
    {
        $video = GalleryVideo::query()->whereNotNull('youtube_id')->firstOrFail();

        $this->get('/gallery/video')
            ->assertOk()
            ->assertSee('12 videos')
            ->assertSee('Science Fair Exhibition')
            ->assertSee('data-video-id="s9xq0rOPltg"', false)
            ->assertSee('data-view-url="'.route('gallery.videos.view', $video).'"', false)
            ->assertDontSee('data-video-id="undefined"', false);
    }

    public function test_video_gallery_shows_newest_videos_first(): void
    {
        $olderVideo = GalleryVideo::create([
            'title' => 'Older test video',
            'category' => 'events',
            'youtube_id' => 'OldVideo123',
            'duration' => '2:00',
            'sort_order' => 0,
        ]);
        $olderVideo->forceFill(['created_at' => now()->subDays(2)])->saveQuietly();

        $newestVideo = GalleryVideo::create([
            'title' => 'Newest test video',
            'category' => 'events',
            'youtube_id' => 'NewVideo123',
            'duration' => '2:00',
            'sort_order' => 100,
        ]);
        $newestVideo->forceFill(['created_at' => now()->addDay()])->saveQuietly();

        $this->get('/gallery/video')
            ->assertOk()
            ->assertViewHas('videos', fn ($videos): bool => $videos->first()->is($newestVideo));
    }

    public function test_opening_a_playable_video_increments_its_view_count(): void
    {
        $video = GalleryVideo::query()->whereNotNull('youtube_id')->firstOrFail();
        $expectedViews = $video->views + 1;

        $this->postJson(route('gallery.videos.view', $video))
            ->assertOk()
            ->assertJson(['views' => $expectedViews]);

        $this->assertSame($expectedViews, $video->fresh()->views);
        $this->get('/gallery/video')
            ->assertOk()
            ->assertSee(number_format($expectedViews).' views');
    }

    public function test_placeholder_video_cannot_increment_a_view_count(): void
    {
        $video = GalleryVideo::query()->whereNull('youtube_id')->firstOrFail();

        $this->postJson(route('gallery.videos.view', $video))->assertNotFound();

        $this->assertSame($video->views, $video->fresh()->views);
    }

    public function test_admin_can_add_edit_and_delete_a_gallery_video(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->post(route('admin.gallery.videos.store'), [
                'title' => 'School Science Showcase',
                'category' => 'academics',
                'youtube_id' => 'AbCdEf12345',
                'duration' => '9:30',
            ])
            ->assertRedirect(route('admin.gallery.videos.index'))
            ->assertSessionHas('status', 'Video added to the gallery.');

        $video = GalleryVideo::query()->where('title', 'School Science Showcase')->firstOrFail();
        $this->assertSame(0, $video->views);
        $this->get('/gallery/video')
            ->assertOk()
            ->assertSee('School Science Showcase')
            ->assertSee('data-video-id="AbCdEf12345"', false);

        $this->put(route('admin.gallery.videos.update', $video), [
            'title' => 'Updated Science Showcase',
            'category' => 'events',
            'youtube_id' => 'ZyXwVu98765',
            'duration' => '10:00',
            'sort_order' => 1,
        ])->assertRedirect(route('admin.gallery.videos.index'))
            ->assertSessionHas('status', 'Video updated.');

        $video->refresh();
        $this->assertSame('Updated Science Showcase', $video->title);
        $this->assertSame('events', $video->category);
        $this->assertSame('ZyXwVu98765', $video->youtube_id);
        $this->assertSame('10:00', $video->duration);
        $this->assertSame(1, $video->sort_order);

        $this->delete(route('admin.gallery.videos.destroy', $video))
            ->assertRedirect(route('admin.gallery.videos.index'))
            ->assertSessionHas('status', 'Video deleted.');

        $this->assertDatabaseMissing('gallery_videos', ['id' => $video->id]);
    }

    public function test_admin_cannot_save_a_video_with_an_invalid_youtube_id(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->from(route('admin.gallery.videos.index'))
            ->post(route('admin.gallery.videos.store'), [
                'title' => 'Invalid Video',
                'category' => 'events',
                'youtube_id' => 'not-a-video-id',
                'duration' => '5:00',
            ])
            ->assertRedirect(route('admin.gallery.videos.index'))
            ->assertSessionHasErrors('youtube_id');

        $this->assertDatabaseMissing('gallery_videos', ['title' => 'Invalid Video']);
    }
}
