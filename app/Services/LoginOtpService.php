<?php

namespace App\Services;

use App\Models\LoginOtp;
use App\Models\User;
use App\Notifications\LoginOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginOtpService
{
    public function issue(User $user, Request $request): LoginOtp
    {
        $expiresInMinutes = (int) config('auth.login_otp.expire', 10);
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $challenge = DB::transaction(function () use ($user, $code, $expiresInMinutes): LoginOtp {
            User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            LoginOtp::query()->where('user_id', $user->id)->delete();

            return LoginOtp::query()->create([
                'user_id' => $user->id,
                'code_hash' => Hash::make($code),
                'attempts' => 0,
                'expires_at' => now()->addMinutes($expiresInMinutes),
                'consumed_at' => null,
            ]);
        });

        $request->session()->put('login_otp_id', $challenge->id);
        $user->notify(new LoginOtpNotification($code, $expiresInMinutes));

        return $challenge;
    }
}
