<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TestimonialManagementController extends Controller
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'student_context' => ['nullable', 'string', 'max:255'],
        ]);
        $filtersAreValid = $validator->passes();
        $filters = $filtersAreValid ? $validator->validated() : [];
        if ($filtersAreValid && array_key_exists('search', $filters)) {
            $filters['search'] = trim((string) ($filters['search'] ?? ''));
        }

        $testimonialsQuery = Testimonial::query();
        if (! $filtersAreValid) {
            $testimonialsQuery->whereRaw('1 = 0');
        } elseif (filled($filters['search'] ?? null)) {
            $search = $filters['search'];
            $testimonialsQuery->where(function (Builder $query) use ($search): void {
                $query->where('parent_name', 'like', "%{$search}%")
                    ->orWhere('student_context', 'like', "%{$search}%")
                    ->orWhere('quote', 'like', "%{$search}%");
            });
        }

        if ($filtersAreValid && filled($filters['student_context'] ?? null)) {
            $testimonialsQuery->where('student_context', $filters['student_context']);
        }

        return view('admin.testimonial-management', [
            'testimonials' => $testimonialsQuery->orderBy('sort_order')->orderBy('id')->get(),
            'testimonialCount' => Testimonial::query()->count(),
            'studentContexts' => Testimonial::query()
                ->select('student_context')
                ->distinct()
                ->orderBy('student_context')
                ->pluck('student_context'),
            'filters' => $filters,
            'filterErrors' => $validator->errors(),
            'hasFilters' => ! $filtersAreValid
                || collect($filters)->contains(fn ($value): bool => filled($value)),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);
        $validated['sort_order'] = (int) Testimonial::query()->max('sort_order') + 1;

        Testimonial::query()->create($validated);
        app(ActivityLogger::class)->record($request, 'Parent recommendation added', $validated['parent_name']);

        return redirect()->route('admin.testimonials.index')->with('status', 'Parent recommendation added.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonial-edit', [
            'testimonial' => $testimonial,
        ]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);
        $testimonial->update($validated);
        app(ActivityLogger::class)->record($request, 'Parent recommendation updated', $validated['parent_name']);

        return redirect()->route('admin.testimonials.index')->with('status', 'Parent recommendation updated.');
    }

    public function destroy(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $parentName = $testimonial->parent_name;
        $testimonial->delete();
        app(ActivityLogger::class)->record($request, 'Parent recommendation deleted', $parentName);

        return redirect()->route('admin.testimonials.index')->with('status', 'Parent recommendation deleted.');
    }

    private function validateTestimonial(Request $request): array
    {
        return $request->validate([
            'parent_name' => ['required', 'string', 'max:255'],
            'student_context' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:2000'],
        ]);
    }
}
