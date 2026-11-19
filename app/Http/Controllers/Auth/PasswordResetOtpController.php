<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\PasswordResetOtpService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordResetOtpController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function send(Request $request, PasswordResetOtpService $otpService): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $this->clearFlow($request);
        $request->session()->put('password_reset_email', $validated['email']);

        $user = User::query()->where('email', $validated['email'])->first();
        if ($user) {
            $otpService->issue($user, $request);
        }

        return redirect()->route('password.otp')
            ->with('status', 'If an account exists for this email, a password reset code has been sent.');
    }

    public function showOtp(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('password_reset_email')) {
            return redirect()->route('password.request');
        }

        if ($request->session()->has('password_reset_verified_id')) {
            return redirect()->route('password.reset');
        }

        $challenge = PasswordResetOtp::query()->find($request->session()->get('password_reset_otp_id'));
        $remainingSeconds = $challenge
            ? max(0, (int) ceil(now()->diffInSeconds($challenge->expires_at, false)))
            : max(0, (int) config('auth.password_reset_otp.expire', 10) * 60);

        return view('auth.password-reset-otp', [
            'expired' => $challenge ? $remainingSeconds === 0 : false,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);
        $challengeId = $request->session()->get('password_reset_otp_id');

        if (! $challengeId) {
            return back()->withErrors(['code' => 'That code is invalid or expired. Request a new code to continue.']);
        }

        $result = DB::transaction(function () use ($challengeId, $validated): array {
            $challenge = PasswordResetOtp::query()->whereKey($challengeId)->lockForUpdate()->first();

            if (! $challenge || $challenge->verified_at || $challenge->consumed_at) {
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
                ];
            }

            $challenge->verified_at = now();
            $challenge->save();

            return ['challenge_id' => $challenge->id];
        });

        if (isset($result['error'])) {
            if (in_array($result['error'], ['unavailable', 'locked'], true)) {
                $request->session()->forget('password_reset_otp_id');
            }

            $message = $result['error'] === 'expired'
                ? 'This code has expired. Request a new code below.'
                : 'That code is invalid or unavailable. Check it or request a new code.';

            return back()->withErrors(['code' => $message])->withInput();
        }

        $request->session()->forget('password_reset_otp_id');
        $request->session()->put('password_reset_verified_id', $result['challenge_id']);

        return redirect()->route('password.reset')
            ->with('status', 'Code verified. Choose a new password.');
    }

    public function resend(Request $request, PasswordResetOtpService $otpService): RedirectResponse
    {
        $email = $request->session()->get('password_reset_email');
        if (! $email) {
            return redirect()->route('password.request');
        }

        $user = User::query()->where('email', $email)->first();
        if ($user) {
            $otpService->issue($user, $request);
        }

        return redirect()->route('password.otp')
            ->with('status', 'If an account exists for this email, a new password reset code has been sent.');
    }

    public function showReset(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('password_reset_email')) {
            return redirect()->route('password.request');
        }

        if (! $request->session()->has('password_reset_verified_id')) {
            return redirect()->route('password.otp');
        }

        if (! $this->verifiedChallenge($request)) {
            $this->clearFlow($request);

            return redirect()->route('password.request')
                ->withErrors(['email' => 'Your password reset code has expired. Request a new code.']);
        }

        return view('auth.reset-password');
    }

    public function update(Request $request): RedirectResponse
    {
        if (! $this->verifiedChallenge($request)) {
            $this->clearFlow($request);

            return redirect()->route('password.request')
                ->withErrors(['email' => 'Your password reset code has expired. Request a new code.']);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        $challengeId = $request->session()->get('password_reset_verified_id');

        $user = DB::transaction(function () use ($challengeId, $validated): ?User {
            $challenge = PasswordResetOtp::query()->whereKey($challengeId)->lockForUpdate()->first();

            if (! $challenge || ! $challenge->verified_at || $challenge->consumed_at || $challenge->expires_at->isPast()) {
                return null;
            }

            $user = User::query()->whereKey($challenge->user_id)->lockForUpdate()->first();
            if (! $user) {
                return null;
            }

            $user->password = $validated['password'];
            $user->remember_token = Str::random(60);
            $user->save();

            $challenge->consumed_at = now();
            $challenge->save();

            return $user;
        });

        if (! $user) {
            $this->clearFlow($request);

            return redirect()->route('password.request')
                ->withErrors(['email' => 'Your password reset code is no longer available. Request a new code.']);
        }

        event(new PasswordReset($user));
        app(ActivityLogger::class)->record($request, 'Password reset', 'Password changed using email OTP', user: $user);
        $this->clearFlow($request);
        $request->session()->regenerate();

        return redirect()->route('login')
            ->with('status', 'Your password has been reset. Sign in with your new password.');
    }

    private function verifiedChallenge(Request $request): ?PasswordResetOtp
    {
        $challengeId = $request->session()->get('password_reset_verified_id');
        if (! $challengeId) {
            return null;
        }

        $challenge = PasswordResetOtp::query()->find($challengeId);

        if (! $challenge || ! $challenge->verified_at || $challenge->consumed_at || $challenge->expires_at->isPast()) {
            return null;
        }

        return $challenge;
    }

    private function clearFlow(Request $request): void
    {
        $request->session()->forget([
            'password_reset_email',
            'password_reset_otp_id',
            'password_reset_verified_id',
        ]);
    }

    private function maxAttempts(): int
    {
        return (int) config('auth.password_reset_otp.max_attempts', 5);
    }
}
