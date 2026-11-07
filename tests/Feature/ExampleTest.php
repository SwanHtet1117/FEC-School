<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_a_post_detail_page_displays_the_requested_post(): void
    {
        $response = $this->get('/news-events/posts/school-open-day');

        $response->assertOk()
            ->assertSee('School Open Day')
            ->assertSee('FEC Campus, Naypyidaw')
            ->assertSee('Event');
    }

    public function test_an_unknown_post_detail_returns_not_found(): void
    {
        $this->get('/news-events/posts/not-a-real-post')->assertNotFound();
    }

    public function test_admin_dashboard_displays_school_overview(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        HeroSlide::create([
            'image_path' => 'image/extra_hero.jpg',
            'alt_text' => 'Additional hero image',
            'sort_order' => 3,
            'is_enabled' => true,
        ]);

        $this->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Admin Dashboard')
            ->assertSee('<span class="stat-copy"><strong>4</strong><small>Hero Images</small></span>', false)
            ->assertSee('System Activities')
            ->assertSee('Quick Actions')
            ->assertSee('Department Performance')
            ->assertSee('Storage Usage');
    }

    public function test_admin_hero_management_page_shows_homepage_slides(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->get('/admin/hero/manage')
            ->assertOk()
            ->assertSee('Home Page Hero Images')
            ->assertSee('FEC International School building')
            ->assertSee('At least one image must remain enabled')
            ->assertSee('id="hero-image-search"', false)
            ->assertSee('id="hero-image-status"', false)
            ->assertSee('>Enabled</option>', false)
            ->assertSee('>Disabled</option>', false)
            ->assertSee('id="hero-image-sort"', false)
            ->assertSee('Reverse display order')
            ->assertSee('Disable image');
    }
}
