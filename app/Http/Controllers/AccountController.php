<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function editProfile(Request $request): View
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->getKey())],
        ]);

        $user->update($validated);
        app(ActivityLogger::class)->record($request, 'Profile updated', 'Account profile');

        return redirect()->route('account.profile.edit')->with('status', 'Profile updated.');
    }

    public function editSettings(): View
    {
        return view('account.settings');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update(['password' => $validated['password']]);
        app(ActivityLogger::class)->record($request, 'Password updated', 'Account password');

        return redirect()->route('account.settings.edit')->with('status', 'Password updated.');
    }
}
