<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\s().-]+$/'],
            'subject' => ['required', 'in:admissions,academic,general,careers,other'],
            'message' => ['required', 'string', 'max:10000'],
        ]);

        ContactMessage::create($validated);

        return redirect()->route('contact')->with('status', 'Your message has been sent. We will be in touch soon.');
    }
}
