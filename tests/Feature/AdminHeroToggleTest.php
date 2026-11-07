<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminHeroToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_an_admin_can_open_hero_management(): void
    {
        $this->get('/admin/hero/manage')->assertRedirect('/login');

        $this->actingAs(User::factory()->create())
            ->get('/admin/hero/manage')
            ->assertForbidden();
    }

    public function test_admin_login_redirects_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Admin Dashboard')
            ->assertSee('System Activities')
            ->assertSee('Manage Hero Images');
    }

    public function test_admin_can_disable_a_hero_image_and_hide_it_from_the_homepage(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $slide = HeroSlide::ordered()->skip(1)->firstOrFail();

        $this->actingAs($admin)
            ->post("/admin/hero/manage/{$slide->id}/toggle")
            ->assertRedirect('/admin/hero/manage')
            ->assertSessionHas('status', 'Hero image disabled.');

        $this->assertFalse($slide->fresh()->is_enabled);
        $homepage = $this->get('/')->assertOk()->getContent();
        preg_match('/data-slides="([^"]*)"/', $homepage, $matches);

        $this->assertNotContains($slide->image_url, explode('|', $matches[1] ?? ''));
    }

    public function test_admin_cannot_disable_the_last_enabled_hero_image(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $slides = HeroSlide::all();
        HeroSlide::where('id', '!=', $slides->first()->id)->update(['is_enabled' => false]);

        $this->actingAs($admin)
            ->post("/admin/hero/manage/{$slides->first()->id}/toggle")
            ->assertRedirect('/admin/hero/manage')
            ->assertSessionHasErrors('hero_slide');

        $this->assertTrue($slides->first()->fresh()->is_enabled);
    }

    public function test_admin_can_reenable_a_disabled_hero_image(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $slide = HeroSlide::ordered()->firstOrFail();
        $slide->update(['is_enabled' => false]);

        $this->actingAs($admin)
            ->post("/admin/hero/manage/{$slide->id}/toggle")
            ->assertRedirect('/admin/hero/manage')
            ->assertSessionHas('status', 'Hero image enabled.');

        $this->assertTrue($slide->fresh()->is_enabled);
    }

    public function test_admin_can_add_a_hero_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $upload = $this->testImage();

        $this->actingAs($admin)
            ->post('/admin/hero/manage', [
                'image' => $upload,
                'alt_text' => 'New campus photo',
            ])
            ->assertRedirect('/admin/hero/manage')
            ->assertSessionHas('status', 'Hero image added.');

        $slide = HeroSlide::where('alt_text', 'New campus photo')->firstOrFail();
        $this->assertSame('hero-slides/'.$upload->hashName(), $slide->image_path);
        $this->assertTrue($slide->is_enabled);
        Storage::disk('public')->assertExists($slide->image_path);
        $this->get('/')
            ->assertOk()
            ->assertSee($slide->image_url, false);
    }

    public function test_admin_can_edit_slide_details_and_replace_an_uploaded_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $oldPath = 'hero-slides/old.png';
        Storage::disk('public')->put($oldPath, 'old image');
        $slide = HeroSlide::create([
            'image_path' => $oldPath,
            'alt_text' => 'Old description',
            'sort_order' => 10,
            'is_enabled' => true,
        ]);
        $replacement = $this->testImage();

        $this->actingAs($admin)
            ->put("/admin/hero/manage/{$slide->id}", [
                'image' => $replacement,
                'alt_text' => 'Updated description',
                'sort_order' => 2,
            ])
            ->assertRedirect('/admin/hero/manage')
            ->assertSessionHas('status', 'Hero image updated.');

        $slide->refresh();
        $this->assertSame('Updated description', $slide->alt_text);
        $this->assertSame(2, $slide->sort_order);
        $this->assertNotSame($oldPath, $slide->image_path);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($slide->image_path);
    }

    public function test_admin_can_delete_a_slide_and_its_uploaded_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $path = 'hero-slides/remove.png';
        Storage::disk('public')->put($path, 'uploaded image');
        $slide = HeroSlide::create([
            'image_path' => $path,
            'alt_text' => 'Removable image',
            'sort_order' => 20,
            'is_enabled' => false,
        ]);

        $this->actingAs($admin)
            ->delete("/admin/hero/manage/{$slide->id}")
            ->assertRedirect('/admin/hero/manage')
            ->assertSessionHas('status', 'Hero image deleted.');

        $this->assertDatabaseMissing('hero_slides', ['id' => $slide->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_admin_cannot_delete_the_last_enabled_slide(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $slide = HeroSlide::firstOrFail();
        HeroSlide::where('id', '!=', $slide->id)->update(['is_enabled' => false]);

        $this->actingAs($admin)
            ->delete("/admin/hero/manage/{$slide->id}")
            ->assertRedirect('/admin/hero/manage')
            ->assertSessionHasErrors('hero_slide');

        $this->assertDatabaseHas('hero_slides', ['id' => $slide->id]);
        $this->assertTrue($slide->fresh()->is_enabled);
    }

    private function test_image(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'hero.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/r4sAAAAASUVORK5CYII=')
        );
    }
}
