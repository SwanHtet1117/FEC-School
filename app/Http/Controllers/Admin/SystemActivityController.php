<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SystemActivityController extends Controller
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'action' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:Success,Failed'],
            'device' => ['nullable', 'in:Desktop,Mobile,Tablet,Unknown'],
        ]);
        $filtersAreValid = $validator->passes();
        $filters = $filtersAreValid ? $validator->validated() : [];
        if ($filtersAreValid && array_key_exists('search', $filters)) {
            $filters['search'] = trim((string) ($filters['search'] ?? ''));
        }
        $activitiesQuery = SystemActivity::query();

        if (! $filtersAreValid) {
            $activitiesQuery->whereRaw('1 = 0');
        } elseif (filled($filters['search'] ?? null)) {
            $search = $filters['search'];
            $activitiesQuery->where(function (Builder $query) use ($search): void {
                $query->where('user_name', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%");
            });
        }

        if ($filtersAreValid && filled($filters['action'] ?? null)) {
            $activitiesQuery->where('action', $filters['action']);
        }

        if ($filtersAreValid && filled($filters['status'] ?? null)) {
            $activitiesQuery->where('status', $filters['status']);
        }

        if ($filtersAreValid && filled($filters['device'] ?? null)) {
            $activitiesQuery->where('device', $filters['device']);
        }

        return view('admin.system-activities', [
            'activities' => $activitiesQuery
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate(25)
                ->appends(array_filter($filters, fn ($value): bool => filled($value))),
            'actions' => SystemActivity::query()->select('action')->distinct()->orderBy('action')->pluck('action'),
            'filters' => $filters,
            'filterErrors' => $validator->errors(),
            'hasFilters' => ! $filtersAreValid
                || collect($filters)->contains(fn ($value): bool => filled($value)),
        ]);
    }
}
