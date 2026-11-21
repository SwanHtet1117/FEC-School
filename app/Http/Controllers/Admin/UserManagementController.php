<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginOtp;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,approved,disabled'],
            'role' => ['nullable', 'in:user,admin'],
        ]);
        $filtersAreValid = $validator->passes();
        $filters = $filtersAreValid ? $validator->validated() : [];
        if ($filtersAreValid && array_key_exists('search', $filters)) {
            $filters['search'] = trim((string) ($filters['search'] ?? ''));
        }

        $usersQuery = User::query();
        if (! $filtersAreValid) {
            $usersQuery->whereRaw('1 = 0');
        } elseif (filled($filters['search'] ?? null)) {
            $search = $filters['search'];
            $usersQuery->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($filtersAreValid && filled($filters['status'] ?? null)) {
            if ($filters['status'] === 'disabled') {
                $usersQuery->where('is_disabled', true);
            } else {
                $usersQuery->where('is_disabled', false)
                    ->where('is_approved', $filters['status'] === 'approved');
            }
        }

        if ($filtersAreValid && filled($filters['role'] ?? null)) {
            $usersQuery->where('is_admin', $filters['role'] === 'admin');
        }

        return view('admin.user-management', [
            'users' => $usersQuery->orderBy('is_disabled')->orderBy('is_approved')->latest()->paginate(15)
                ->appends(array_filter($filters, fn ($value): bool => filled($value))),
            'userCount' => User::count(),
            'pendingCount' => User::where('is_approved', false)->where('is_disabled', false)->count(),
            'approvedCount' => User::where('is_approved', true)->where('is_disabled', false)->count(),
            'disabledCount' => User::where('is_disabled', true)->count(),
            'adminCount' => User::where('is_admin', true)->count(),
            'activeAdminCount' => User::where('is_admin', true)->where('is_disabled', false)->count(),
            'filters' => $filters,
            'filterErrors' => $validator->errors(),
            'hasFilters' => ! $filtersAreValid
                || collect($filters)->contains(fn ($value): bool => filled($value)),
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

    public function promote(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        $result = DB::transaction(function () use ($user): array {
            $target = User::query()->lockForUpdate()->findOrFail($user->getKey());

            if ($target->is_admin) {
                return ['status' => 'already_admin'];
            }

            if (! $target->is_approved) {
                return ['status' => 'pending'];
            }

            if ($target->is_disabled) {
                return ['status' => 'disabled'];
            }

            $target->is_admin = true;
            $target->save();

            return ['status' => 'promoted', 'name' => $target->name];
        });

        if ($result['status'] !== 'promoted') {
            $message = match ($result['status']) {
                'pending' => 'Approve this account before promoting it to administrator.',
                'disabled' => 'Enable this account before promoting it to administrator.',
                default => 'This account is already an administrator.',
            };

            return redirect()->route('admin.users.index')
                ->withErrors(['user' => $message]);
        }

        $activityLogger->record($request, 'User promoted to administrator', $result['name']);

        return redirect()->route('admin.users.index')
            ->with('status', "{$result['name']} was promoted to administrator.");
    }

    public function disable(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return redirect()->route('admin.users.index')
                ->withErrors(['user' => 'You cannot disable your own administrator account.']);
        }

        $result = DB::transaction(function () use ($user): array {
            $admins = User::query()
                ->where('is_admin', true)
                ->orderBy('id')
                ->lockForUpdate()
                ->get(['id', 'is_disabled']);
            $target = User::query()->lockForUpdate()->findOrFail($user->getKey());

            if ($target->is_disabled) {
                return ['status' => 'already_disabled'];
            }

            if ($target->is_admin && $admins->where('is_disabled', false)->count() <= 1) {
                return ['status' => 'last_active_admin'];
            }

            $target->is_disabled = true;
            $target->save();
            LoginOtp::query()
                ->where('user_id', $target->getKey())
                ->whereNull('consumed_at')
                ->update(['consumed_at' => now()]);

            return ['status' => 'disabled', 'name' => $target->name];
        });

        if ($result['status'] !== 'disabled') {
            $message = $result['status'] === 'last_active_admin'
                ? 'You cannot disable the last active administrator account.'
                : 'This account is already disabled.';

            return redirect()->route('admin.users.index')->withErrors(['user' => $message]);
        }

        $activityLogger->record($request, 'User account disabled', $result['name']);

        return redirect()->route('admin.users.index')
            ->with('status', "{$result['name']}'s account was disabled.");
    }

    public function enable(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        $result = DB::transaction(function () use ($user): array {
            $target = User::query()->lockForUpdate()->findOrFail($user->getKey());

            if (! $target->is_disabled) {
                return ['status' => 'already_enabled'];
            }

            $target->is_disabled = false;
            $target->save();

            return ['status' => 'enabled', 'name' => $target->name];
        });

        if ($result['status'] !== 'enabled') {
            return redirect()->route('admin.users.index')
                ->withErrors(['user' => 'This account is already enabled.']);
        }

        $activityLogger->record($request, 'User account enabled', $result['name']);

        return redirect()->route('admin.users.index')
            ->with('status', "{$result['name']}'s account was enabled.");
    }

    public function destroy(Request $request, User $user, ActivityLogger $activityLogger): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return redirect()->route('admin.users.index')
                ->withErrors(['user' => 'You cannot delete your own administrator account.']);
        }

        $deleted = DB::transaction(function () use ($user): bool {
            $admins = User::query()
                ->where('is_admin', true)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();
            $target = User::query()->lockForUpdate()->findOrFail($user->getKey());

            if ($target->is_admin) {
                if ($admins->count() <= 1) {
                    return false;
                }

                if (! $target->is_disabled && $admins->where('is_disabled', false)->count() <= 1) {
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
