<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ContactMessageManagementController extends Controller
{
    public function index(Request $request): View
    {
        $subjects = [
            'admissions' => 'Admissions Inquiry',
            'academic' => 'Academic Inquiry',
            'general' => 'General Inquiry',
            'careers' => 'Careers Inquiry',
            'other' => 'Other',
        ];
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'subject' => ['nullable', 'in:admissions,academic,general,careers,other'],
            'status' => ['nullable', 'in:read,unread'],
        ]);
        $filtersAreValid = $validator->passes();
        $filters = $filtersAreValid ? $validator->validated() : [];
        if ($filtersAreValid && array_key_exists('search', $filters)) {
            $filters['search'] = trim((string) ($filters['search'] ?? ''));
        }

        $messagesQuery = ContactMessage::query();
        if (! $filtersAreValid) {
            $messagesQuery->whereRaw('1 = 0');
        } elseif (filled($filters['search'] ?? null)) {
            $search = $filters['search'];
            $messagesQuery->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($filtersAreValid && filled($filters['subject'] ?? null)) {
            $messagesQuery->where('subject', $filters['subject']);
        }

        if ($filtersAreValid && filled($filters['status'] ?? null)) {
            $messagesQuery->where('is_read', $filters['status'] === 'read');
        }

        return view('admin.contact-message-management', [
            'messages' => $messagesQuery->latest()->paginate(15)
                ->appends(array_filter($filters, fn ($value): bool => filled($value))),
            'messageCount' => ContactMessage::count(),
            'unreadCount' => ContactMessage::where('is_read', false)->count(),
            'readCount' => ContactMessage::where('is_read', true)->count(),
            'subjects' => $subjects,
            'filters' => $filters,
            'filterErrors' => $validator->errors(),
            'hasFilters' => ! $filtersAreValid
                || collect($filters)->contains(fn ($value): bool => filled($value)),
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
