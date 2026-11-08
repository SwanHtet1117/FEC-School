<?php

namespace App\Services;

use App\Models\SystemActivity;
use App\Models\User;
use DateTimeZone;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function record(
        Request $request,
        string $action,
        string $details,
        string $status = 'Success',
        ?User $user = null
    ): void {
        $user ??= $request->user();
        $userAgent = strtolower($request->userAgent() ?? '');
        $timezone = $this->timezone($request);

        $device = match (true) {
            $userAgent === '' => 'Unknown',
            preg_match('/ipad|tablet|kindle|silk|playbook/', $userAgent) === 1 => 'Tablet',
            preg_match('/mobile|iphone|ipod|android.*mobile|windows phone/', $userAgent) === 1 => 'Mobile',
            default => 'Desktop',
        };

        SystemActivity::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Unknown User',
            'action' => $action,
            'details' => $details,
            'status' => $status,
            'device' => $device,
            'timezone' => $timezone,
        ]);
    }

    public function timezone(Request $request): string
    {
        $requestedTimezone = $request->input('timezone', $request->session()->get('device_timezone'));

        return is_string($requestedTimezone)
            && ($requestedTimezone === 'UTC'
                || in_array($requestedTimezone, DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC), true))
                    ? $requestedTimezone
                    : 'UTC';
    }
}
