<?php

namespace Tests\Feature;

use App\Models\GalleryPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryPhotoManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_an_admin_can_open_photo_management(): void
    {
        $this->get(route('admin.gallery.photos.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.gallery.photos.index'))
            ->assertForbidden();
    }

    public function test_existing_gallery_photos_are_displayed_on_the_public_page(): void
    {
        $this->get('/gallery/photo')
            ->assertOk()
            ->assertSee('School Gathering')
            ->assertSee('Students gathered outdoors at school')
            ->assertSee('12 photos');
    }

    public function test_opening_a_gallery_photo_increments_its_view_count(): void
    {
        $photo = GalleryPhoto::ordered()->firstOrFail();
        $expectedViews = $photo->views + 1;

        $this->postJson(route('gallery.photos.view', $photo))
            ->assertOk()
            ->assertJson(['views' => $expectedViews]);

        $this->assertSame($expectedViews, $photo->fresh()->views);
        $this->get('/gallery/photo')
            ->assertOk()
            ->assertSee(number_format($expectedViews).' views');
    }

    public function test_campus_gallery_shows_only_the_first_eight_campus_life_photos(): void
    {
        foreach (range(1, 9) as $number) {
            GalleryPhoto::create([
                'title' => "Campus Test Photo {$number}",
                'category' => 'campus-life',
                'image_path' => 'fec_building.jpg',
                'alt_text' => "Campus test photo {$number}",
                'views' => 0,
                'sort_order' => 20 + $number,
            ]);
        }

        GalleryPhoto::create([
            'title' => 'Academic Test Photo',
            'category' => 'academics',
            'image_path' => 'fec_building.jpg',
            'alt_text' => 'Academic test photo',
            'views' => 0,
            'sort_order' => 0,
        ]);

        $response = $this->get('/campus-life')
            ->assertOk()
            ->assertSee('View all campus life photos')
            ->assertViewHas('campusGalleryPhotos', function ($photos): bool {
                return $photos->count() === 8
                    && $photos->every(fn (GalleryPhoto $photo): bool => $photo->category === 'campus-life');
            })
            ->assertDontSee('Academic Test Photo');

        $this->assertSame(
            3,
            substr_count($response->getContent(), 'href="/gallery/photo?category=campus-life"')
        );
    }

    public function test_admin_can_add_edit_replace_and_delete_gallery_photos(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $upload = $this->testImage('science-fair.png');

        $this->actingAs($admin)
            ->post(route('admin.gallery.photos.store'), [
                'image' => $upload,
                'title' => 'Science Fair',
                'category' => 'academics',
                'alt_text' => 'Students presenting science projects',
            ])
            ->assertRedirect(route('admin.gallery.photos.index'))
            ->assertSessionHas('status', 'Photo added to the gallery.');

        $photo = GalleryPhoto::query()->where('title', 'Science Fair')->firstOrFail();
        Storage::disk('public')->assertExists($photo->image_path);
        $this->get('/gallery/photo')
            ->assertOk()
            ->assertSee('Science Fair')
            ->assertSee($photo->image_url, false);

        $replacement = $this->testImage('science-fair-updated.png');
        $oldPath = $photo->image_path;
        $this->put(route('admin.gallery.photos.update', $photo), [
            'title' => 'Science Fair Showcase',
            'category' => 'events',
            'alt_text' => 'Students sharing their science fair projects',
            'sort_order' => 1,
            'image' => $replacement,
        ])->assertRedirect(route('admin.gallery.photos.index'));

        $photo->refresh();
        $this->assertSame('Science Fair Showcase', $photo->title);
        $this->assertSame('events', $photo->category);
        $this->assertSame(1, $photo->sort_order);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($photo->image_path);

        $this->delete(route('admin.gallery.photos.destroy', $photo))
            ->assertRedirect(route('admin.gallery.photos.index'))
            ->assertSessionHas('status', 'Photo deleted.');

        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing($photo->image_path);
    }

    public function test_admin_cannot_save_a_photo_with_an_unknown_category(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->from(route('admin.gallery.photos.index'))
            ->post(route('admin.gallery.photos.store'), [
                'image' => $this->testImage(),
                'title' => 'Invalid category',
                'category' => 'other',
                'alt_text' => 'A sample photo',
            ])
            ->assertRedirect(route('admin.gallery.photos.index'))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseMissing('gallery_photos', ['title' => 'Invalid category']);
    }

    private function test_image(string $filename = 'gallery.png'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $filename,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/r4sAAAAASUVORK5CYII=')
        );
    }
}
