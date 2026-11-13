<?php

namespace Tests\Feature;

use App\Models\CareerOpportunity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerOpportunityManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admins_can_open_career_management(): void
    {
        $this->get(route('admin.careers.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.careers.index'))
            ->assertForbidden();
    }

    public function test_admin_can_publish_a_career_opportunity_on_the_contact_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.careers.store'), [
                'title' => 'Primary English Teacher',
                'department' => 'Primary School',
                'employment_type' => 'Full-time',
                'location' => 'Naypyidaw',
                'description' => 'Teach English to primary students.',
                'application_email' => 'careers@example.com',
                'closes_at' => today()->addWeek()->toDateString(),
            ])
            ->assertRedirect(route('admin.careers.index'))
            ->assertSessionHas('status', 'Career opportunity published.');

        $opportunity = CareerOpportunity::firstOrFail();
        $this->assertSame('Primary English Teacher', $opportunity->title);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Primary English Teacher')
            ->assertSee('Primary School')
            ->assertSee('mailto:careers@example.com?subject=Application%3A%20Primary%20English%20Teacher', false);
    }

    public function test_expired_opportunities_are_hidden_from_the_contact_page_and_open_count(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $expiredOpportunity = $this->createOpportunity([
            'title' => 'Expired Teacher',
            'closes_at' => today()->subDay(),
        ]);
        $this->createOpportunity(['title' => 'Current Teacher']);

        $this->get(route('contact'))
            ->assertOk()
            ->assertDontSee('Expired Teacher')
            ->assertSee('Current Teacher');

        $this->actingAs($admin)
            ->get(route('admin.careers.index'))
            ->assertOk()
            ->assertSee('Expired Teacher')
            ->assertSee('1 Open Position');
    }

    public function test_admin_can_filter_career_opportunities_by_search_type_and_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->createOpportunity([
            'title' => 'Primary English Teacher',
            'department' => 'Primary School',
            'employment_type' => 'Full-time',
        ]);
        $this->createOpportunity([
            'title' => 'Primary Music Teacher',
            'department' => 'Primary School',
            'employment_type' => 'Full-time',
            'closes_at' => today()->subDay(),
        ]);
        $this->createOpportunity([
            'title' => 'Primary Teaching Assistant',
            'department' => 'Primary School',
            'employment_type' => 'Part-time',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.careers.index', [
                'search' => 'Primary School',
                'employment_type' => 'Full-time',
                'status' => 'Open',
            ]))
            ->assertOk()
            ->assertSee('Primary English Teacher')
            ->assertDontSee('Primary Music Teacher')
            ->assertDontSee('Primary Teaching Assistant')
            ->assertSee('Showing 1 of 3 opportunities')
            ->assertSee('Clear');

        $this->get(route('admin.careers.index', ['status' => ['invalid']]))
            ->assertOk()
            ->assertSee('Some filter values are invalid.')
            ->assertSee('Please correct the filters above');
    }

    public function test_admin_can_delete_a_career_opportunity(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $opportunity = $this->createOpportunity();

        $this->actingAs($admin)
            ->delete(route('admin.careers.destroy', $opportunity))
            ->assertRedirect(route('admin.careers.index'))
            ->assertSessionHas('status', 'Career opportunity deleted.');

        $this->assertDatabaseMissing('career_opportunities', ['id' => $opportunity->id]);
    }

    public function test_admin_can_edit_a_career_opportunity(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $opportunity = $this->createOpportunity();

        $this->actingAs($admin)
            ->get(route('admin.careers.edit', $opportunity))
            ->assertOk()
            ->assertSee('Edit Career Opportunity')
            ->assertSee('value="School Counselor"', false);

        $this->put(route('admin.careers.update', $opportunity), [
            'title' => 'Senior School Counselor',
            'department' => 'Student Support',
            'employment_type' => 'Part-time',
            'location' => 'Naypyidaw',
            'description' => 'Provide academic and emotional support.',
            'application_email' => 'jobs@example.com',
            'closes_at' => null,
        ])
            ->assertRedirect(route('admin.careers.index'))
            ->assertSessionHas('status', 'Career opportunity updated.');

        $this->assertSame('Senior School Counselor', $opportunity->fresh()->title);
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Senior School Counselor')
            ->assertSee('mailto:jobs@example.com?subject=Application%3A%20Senior%20School%20Counselor', false);
    }

    public function test_career_opportunity_requires_valid_fields_and_non_past_deadline(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->from(route('admin.careers.index'))
            ->post(route('admin.careers.store'), [
                'title' => '',
                'department' => 'Primary School',
                'employment_type' => 'Full-time',
                'location' => 'Naypyidaw',
                'description' => 'A role description.',
                'application_email' => 'not-an-email',
                'closes_at' => today()->subDay()->toDateString(),
            ])
            ->assertRedirect(route('admin.careers.index'))
            ->assertSessionHasErrors(['title', 'application_email', 'closes_at']);

        $this->assertDatabaseCount('career_opportunities', 0);
    }

    private function createOpportunity(array $attributes = []): CareerOpportunity
    {
        return CareerOpportunity::create(array_merge([
            'title' => 'School Counselor',
            'department' => 'Student Services',
            'employment_type' => 'Full-time',
            'location' => 'Naypyidaw',
            'description' => 'Support students.',
            'application_email' => 'careers@example.com',
            'closes_at' => null,
        ], $attributes));
    }
}
