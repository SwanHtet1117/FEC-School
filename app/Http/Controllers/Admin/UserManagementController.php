<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.user-management', [
            'users' => User::query()->orderBy('is_approved')->latest()->paginate(15),
            'userCount' => User::count(),
            'pendingCount' => User::where('is_approved', false)->count(),
            'approvedCount' => User::where('is_approved', true)->count(),
            'adminCount' => User::where('is_admin', true)->count(),
        ]);
    }

    public function approve(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        if ($user->is_approved) {
            return redirect()->route('admin.users.index')
                ->withErrors(['user' => 'This account has already been approved.']);
        }

        if ($user->is_admin) {
            return redirect()->route('admin.users.index')
                ->withErrors(['user' => 'Administrator accounts cannot be approved from this page.']);
        }

        $user->update(['is_approved' => true]);
        $activityLogger->record($request, 'User approved', $user->name);

        return redirect()->route('admin.users.index')->with('status', "{$user->name}'s account was approved.");
    }

    public function destroy(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return redirect()->route('admin.users.index')
                ->withErrors(['user' => 'You cannot delete your own administrator account.']);
        }

        $deleted = DB::transaction(function () use ($user): bool {
            $target = User::query()->lockForUpdate()->findOrFail($user->getKey());

            if ($target->is_admin) {
                $admins = User::query()->where('is_admin', true)->lockForUpdate()->get();

                if ($admins->count() <= 1) {
                    return false;
                }
            }

            $target->delete();

            return true;
        });

        if (! $deleted) {
            return redirect()->route('admin.users.index')
                ->withErrors(['user' => 'The last administrator account cannot be deleted.']);
        }

        $activityLogger->record($request, 'User deleted', $user->name);

        return redirect()->route('admin.users.index')->with('status', "{$user->name}'s account was deleted.");
    }
}
