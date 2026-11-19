<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginOtp;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\LoginOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginOtpController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $challenge = LoginOtp::query()->find($request->session()->get('login_otp_id'));
        if (! $challenge || $challenge->consumed_at || $challenge->attempts >= $this->maxAttempts()) {
            $this->clearChallenge($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'Your sign-in code is no longer available. Please sign in again.']);
        }

        $remainingSeconds = max(0, (int) ceil(now()->diffInSeconds($challenge->expires_at, false)));

        return view('auth.login-otp', [
            'expired' => $remainingSeconds === 0,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);
        $challengeId = $request->session()->get('login_otp_id');

        if (! $challengeId) {
            $this->clearChallenge($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'Your sign-in code is no longer available. Please sign in again.']);
        }

        $result = DB::transaction(function () use ($challengeId, $validated): array {
            $challenge = LoginOtp::query()
                ->whereKey($challengeId)
                ->lockForUpdate()
                ->first();

            if (! $challenge || $challenge->consumed_at) {
                return ['error' => 'unavailable'];
            }

            if ($challenge->expires_at->isPast()) {
                return ['error' => 'expired'];
            }

            if ($challenge->attempts >= $this->maxAttempts()) {
                return ['error' => 'locked'];
            }

            if (! Hash::check($validated['code'], $challenge->code_hash)) {
                $challenge->attempts++;
                if ($challenge->attempts >= $this->maxAttempts()) {
                    $challenge->consumed_at = now();
                }
                $challenge->save();

                return [
                    'error' => $challenge->attempts >= $this->maxAttempts() ? 'locked' : 'invalid',
                    'user_id' => $challenge->user_id,
                ];
            }

            $challenge->consumed_at = now();
            $challenge->save();

            return ['user_id' => $challenge->user_id];
        });

        if (isset($result['error'])) {
            if (isset($result['user_id'])) {
                $user = User::query()->find($result['user_id']);
                if ($user) {
                    app(ActivityLogger::class)->record($request, 'Login', 'Invalid email OTP', 'Failed', $user);
                }
            }

            if (in_array($result['error'], ['unavailable', 'locked'], true)) {
                $this->clearChallenge($request);

                return redirect()->route('login')
                    ->withErrors(['email' => 'Too many or invalid sign-in attempts. Please sign in again.']);
            }

            $message = $result['error'] === 'expired'
                ? 'This code has expired. Request a new code below.'
                : 'That code is incorrect. Please try again.';

            return back()->withErrors(['code' => $message])->withInput();
        }

        $user = User::query()->find($result['user_id']);
        if (! $user) {
            $this->clearChallenge($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account could not be found. Please sign in again.']);
        }

        if (! $user->is_admin && ! $user->hasVerifiedEmail()) {
            $this->clearChallenge($request);
            app(ActivityLogger::class)->record($request, 'Login', 'Email address not verified', 'Failed', $user);

            return redirect()->route('login')
                ->withErrors(['email' => 'Please verify your email address before signing in.']);
        }

        if (! $user->is_approved) {
            $this->clearChallenge($request);
            app(ActivityLogger::class)->record($request, 'Login', 'Account awaiting admin approval', 'Failed', $user);

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is awaiting admin approval.']);
        }

        $remember = (bool) $request->session()->pull('login_otp_remember', false);
        $timezone = (string) $request->session()->pull('login_otp_timezone', 'UTC');
        $request->session()->forget('login_otp_id');
        Auth::login($user, $remember);
        $request->session()->regenerate();
        $request->session()->put('device_timezone', $timezone);
        app(ActivityLogger::class)->record($request, 'Login', 'Signed in using email OTP');

        return $user->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('account.dashboard');
    }

    public function resend(Request $request): RedirectResponse
    {
        $challenge = LoginOtp::query()->with('user')->find($request->session()->get('login_otp_id'));
        if (! $challenge || $challenge->consumed_at || $challenge->attempts >= $this->maxAttempts() || ! $challenge->user) {
            $this->clearChallenge($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'Your sign-in code is no longer available. Please sign in again.']);
        }

        $user = $challenge->user;
        if (! $user->is_approved || (! $user->is_admin && ! $user->hasVerifiedEmail())) {
            $this->clearChallenge($request);

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is not currently able to sign in.']);
        }

        app(LoginOtpService::class)->issue($user, $request);

        return redirect()->route('login.otp')
            ->with('status', 'A new one-time sign-in code has been sent to your email.');
    }

    private function clearChallenge(Request $request): void
    {
        $request->session()->forget([
            'login_otp_id',
            'login_otp_remember',
            'login_otp_timezone',
        ]);
    }

    private function maxAttempts(): int
    {
        return (int) config('auth.login_otp.max_attempts', 5);
    }
}
