<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\LoginOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::validate($credentials)) {
            app(ActivityLogger::class)->record(
                $request,
                'Login',
                'Invalid credentials',
                'Failed'
            );

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $user = User::query()->where('email', $credentials['email'])->first();
        if (! $user) {
            app(ActivityLogger::class)->record(
                $request,
                'Login',
                'Invalid credentials',
                'Failed',
            );

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if (! $user->is_admin && ! $user->hasVerifiedEmail()) {
            app(ActivityLogger::class)->record(
                $request,
                'Login',
                'Email address not verified',
                'Failed',
                $user
            );

            throw ValidationException::withMessages([
                'email' => 'Please verify your email address before signing in.',
            ]);
        }

        if (! $user->is_approved) {
            app(ActivityLogger::class)->record(
                $request,
                'Login',
                'Account awaiting admin approval',
                'Failed',
                $user
            );

            throw ValidationException::withMessages([
                'email' => 'Your account is awaiting admin approval.',
            ]);
        }

        $request->session()->put([
            'login_otp_remember' => $request->boolean('remember'),
            'login_otp_timezone' => app(ActivityLogger::class)->timezone($request),
        ]);
        app(LoginOtpService::class)->issue($user, $request);

        return redirect()->route('login.otp')
            ->with('status', 'A one-time sign-in code has been sent to your email.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        app(ActivityLogger::class)->record($request, 'Logout', 'Signed out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
