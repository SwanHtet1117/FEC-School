<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(Request $request): View
    {
        return view('auth.verify-email', [
            'email' => $request->session()->get('verificationEmail'),
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();
        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()->route('verification.notice')
            ->withInput(['email' => $validated['email']])
            ->with('status', 'If that email needs verification, a new link has been sent.');
    }

    public function verify(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        abort_unless(hash_equals(sha1($user->getEmailForVerification()), $hash), 403);

        if (! $user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
            event(new Verified($user));
            app(ActivityLogger::class)->record($request, 'Email verified', 'Email address verified', user: $user);
        }

        $status = $user->is_approved
            ? 'Your email address is verified. You can now sign in.'
            : 'Your email address is verified. Your account is awaiting admin approval.';

        return redirect()->route('login')->with('status', $status);
    }
}
