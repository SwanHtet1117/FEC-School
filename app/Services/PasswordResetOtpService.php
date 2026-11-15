<?php

namespace App\Services;

use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Notifications\PasswordResetOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordResetOtpService
{
    public function issue(User $user, Request $request): PasswordResetOtp
    {
        $expiresInMinutes = (int) config('auth.password_reset_otp.expire', 10);
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $challenge = DB::transaction(function () use ($user, $code, $expiresInMinutes): PasswordResetOtp {
            User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            PasswordResetOtp::query()->where('user_id', $user->id)->delete();

            return PasswordResetOtp::query()->create([
                'user_id' => $user->id,
                'code_hash' => Hash::make($code),
                'attempts' => 0,
                'expires_at' => now()->addMinutes($expiresInMinutes),
                'verified_at' => null,
                'consumed_at' => null,
            ]);
        });

        $request->session()->put([
            'password_reset_email' => $user->email,
            'password_reset_otp_id' => $challenge->id,
        ]);
        $request->session()->forget('password_reset_verified_id');
        $user->notify(new PasswordResetOtpNotification($code, $expiresInMinutes));

        return $challenge;
    }
}
