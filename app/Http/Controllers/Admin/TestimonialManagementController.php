<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonial-management', [
            'testimonials' => Testimonial::query()->orderBy('sort_order')->orderBy('id')->get(),
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
