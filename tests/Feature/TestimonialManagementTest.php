<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admins_can_open_testimonial_management(): void
    {
        $this->get(route('admin.testimonials.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.testimonials.index'))
            ->assertForbidden();
    }

    public function test_admin_can_add_edit_and_delete_homepage_testimonials(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->post(route('admin.testimonials.store'), [
                'parent_name' => 'U Aung Aung',
                'student_context' => 'Parent of Grade 4 Student',
                'quote' => 'The school provides a welcoming learning environment.',
            ])
            ->assertRedirect(route('admin.testimonials.index'))
            ->assertSessionHas('status', 'Parent recommendation added.');

        $testimonial = Testimonial::query()->where('parent_name', 'U Aung Aung')->firstOrFail();

        $this->put(route('admin.testimonials.update', $testimonial), [
            'parent_name' => 'U Aung Win',
            'student_context' => 'Parent of Grade 5 Student',
            'quote' => 'Our child enjoys learning at FEC.',
        ])
            ->assertRedirect(route('admin.testimonials.index'))
            ->assertSessionHas('status', 'Parent recommendation updated.');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Daw Mya Mya')
            ->assertSee('U Aung Win')
            ->assertSee('Our child enjoys learning at FEC.')
            ->assertSee('data-testimonial-next', false)
            ->assertSee('data-testimonial-card hidden', false);

        $this->delete(route('admin.testimonials.destroy', $testimonial))
            ->assertRedirect(route('admin.testimonials.index'))
            ->assertSessionHas('status', 'Parent recommendation deleted.');

        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
        $this->get(route('home'))->assertDontSee('U Aung Win');
    }
}
