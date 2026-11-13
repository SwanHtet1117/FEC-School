<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admins_can_open_faq_management(): void
    {
        $this->get(route('admin.faqs.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.faqs.index'))
            ->assertForbidden();
    }

    public function test_admin_can_add_edit_and_delete_faqs_shown_on_admissions_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->post(route('admin.faqs.store'), [
                'question' => 'How can I book a school tour?',
                'answer' => 'Contact the admissions office to arrange a visit.',
            ])
            ->assertRedirect(route('admin.faqs.index'))
            ->assertSessionHas('status', 'FAQ added.');

        $faq = Faq::query()->where('question', 'How can I book a school tour?')->firstOrFail();

        $this->put(route('admin.faqs.update', $faq), [
            'question' => 'How do I arrange a school tour?',
            'answer' => 'Contact admissions to arrange a visit.',
        ])
            ->assertRedirect(route('admin.faqs.index'))
            ->assertSessionHas('status', 'FAQ updated.');

        $this->get('/admissions')
            ->assertOk()
            ->assertSee('What is the age requirement for admission?')
            ->assertSee('How do I arrange a school tour?')
            ->assertSee('Contact admissions to arrange a visit.');

        $this->delete(route('admin.faqs.destroy', $faq))
            ->assertRedirect(route('admin.faqs.index'))
            ->assertSessionHas('status', 'FAQ deleted.');

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
        $this->get('/admissions')->assertDontSee('How do I arrange a school tour?');
    }

    public function test_admin_can_filter_faqs_by_question_or_answer_and_invalid_values_are_reported(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Faq::query()->create([
            'question' => 'Are scholarships available?',
            'answer' => 'Ask the admissions office about financial aid.',
            'sort_order' => 7,
        ]);
        Faq::query()->create([
            'question' => 'What are the school hours?',
            'answer' => 'The school day begins in the morning.',
            'sort_order' => 8,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.faqs.index', ['search' => 'financial aid']))
            ->assertOk()
            ->assertSee('Are scholarships available?')
            ->assertDontSee('What are the school hours?')
            ->assertSee('Showing 1 of 8 FAQs')
            ->assertSee('Clear');

        $this->get(route('admin.faqs.index', ['search' => ['not-a-string']]))
            ->assertOk()
            ->assertSee('Some filter values are invalid.')
            ->assertSee('Please correct the filters above');
    }
}
