<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.contact-message-management', [
            'messages' => ContactMessage::query()->latest()->paginate(15),
            'messageCount' => ContactMessage::count(),
            'unreadCount' => ContactMessage::where('is_read', false)->count(),
            'readCount' => ContactMessage::where('is_read', true)->count(),
        ]);
    }

    public function toggleRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['is_read' => ! $contactMessage->is_read]);

        return redirect()->route('admin.messages.index')
            ->with('status', $contactMessage->is_read ? 'Message marked as read.' : 'Message marked as unread.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()->route('admin.messages.index')->with('status', 'Message deleted.');
    }
}
