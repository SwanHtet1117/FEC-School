<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
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
}
