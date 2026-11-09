<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_submission_is_saved_for_admin_review(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee(route('contact.messages.store'))
            ->assertSee('_token');

        $this->post(route('contact.messages.store'), [
            'name' => 'A Parent',
            'email' => 'parent@example.com',
            'phone' => '+959123456789',
            'subject' => 'admissions',
            'message' => 'Please share the admission requirements.',
        ])
            ->assertRedirect(route('contact'))
            ->assertSessionHas('status', 'Your message has been sent. We will be in touch soon.');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'A Parent',
            'email' => 'parent@example.com',
            'subject' => 'admissions',
            'message' => 'Please share the admission requirements.',
            'is_read' => false,
        ]);
    }

    public function test_contact_form_rejects_invalid_subjects(): void
    {
        $this->from(route('contact'))
            ->post(route('contact.messages.store'), [
                'name' => 'A Parent',
                'email' => 'parent@example.com',
                'phone' => '+959123456789',
                'subject' => 'not-a-subject',
                'message' => 'A question',
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHasErrors('subject');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_requires_every_field_and_validates_phone_numbers(): void
    {
        $this->from(route('contact'))
            ->post(route('contact.messages.store'), [
                'name' => 'A Parent',
                'email' => 'parent@example.com',
                'phone' => '',
                'subject' => 'general',
                'message' => 'A question',
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHasErrors('phone');

        $this->from(route('contact'))
            ->post(route('contact.messages.store'), [
                'name' => 'A Parent',
                'email' => 'parent@example.com',
                'phone' => 'call me',
                'subject' => 'general',
                'message' => 'A question',
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_only_admins_can_open_the_contact_message_inbox(): void
    {
        $this->get(route('admin.messages.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.messages.index'))
            ->assertForbidden();
    }

    public function test_admin_dashboard_shows_the_total_contact_message_count(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        ContactMessage::create([
            'name' => 'A Parent',
            'email' => 'parent@example.com',
            'subject' => 'general',
            'message' => 'Could you contact me?',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('<strong>1</strong><small>Messages</small>', false);
    }

    public function test_admin_can_review_messages_mark_read_and_delete_them(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $message = ContactMessage::create([
            'name' => 'A Parent',
            'email' => 'parent@example.com',
            'subject' => 'general',
            'message' => 'Could you contact me?',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('A Parent')
            ->assertSee('Could you contact me?')
            ->assertSee('Unread');

        $this->post(route('admin.messages.toggle-read', $message))
            ->assertRedirect(route('admin.messages.index'))
            ->assertSessionHas('status', 'Message marked as read.');
        $this->assertTrue($message->fresh()->is_read);

        $this->delete(route('admin.messages.destroy', $message))
            ->assertRedirect(route('admin.messages.index'))
            ->assertSessionHas('status', 'Message deleted.');
        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }
}
