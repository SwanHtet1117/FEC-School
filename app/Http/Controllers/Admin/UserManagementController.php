<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'status' => ['nullable', 'in:pending,approved'],
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
            $usersQuery->where('is_approved', $filters['status'] === 'approved');
        }

        if ($filtersAreValid && filled($filters['role'] ?? null)) {
            $usersQuery->where('is_admin', $filters['role'] === 'admin');
        }

        return view('admin.user-management', [
            'users' => $usersQuery->orderBy('is_approved')->latest()->paginate(15)
                ->appends(array_filter($filters, fn ($value): bool => filled($value))),
            'userCount' => User::count(),
            'pendingCount' => User::where('is_approved', false)->count(),
            'approvedCount' => User::where('is_approved', true)->count(),
            'adminCount' => User::where('is_admin', true)->count(),
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
