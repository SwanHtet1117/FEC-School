<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunity;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerOpportunityController extends Controller
{
    public function index(): View
    {
        return view('admin.career-opportunities', [
            'opportunities' => CareerOpportunity::query()->latest()->get(),
            'openCount' => CareerOpportunity::open()->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateOpportunity($request);

        CareerOpportunity::create($validated);
        app(ActivityLogger::class)->record($request, 'Career opportunity added', $validated['title']);

        return redirect()->route('admin.careers.index')->with('status', 'Career opportunity published.');
    }

    public function edit(CareerOpportunity $careerOpportunity): View
    {
        return view('admin.career-opportunity-edit', [
            'opportunity' => $careerOpportunity,
        ]);
    }

    public function update(Request $request, CareerOpportunity $careerOpportunity): RedirectResponse
    {
        $validated = $this->validateOpportunity($request, true);

        $careerOpportunity->update($validated);
        app(ActivityLogger::class)->record($request, 'Career opportunity updated', $validated['title']);

        return redirect()->route('admin.careers.index')->with('status', 'Career opportunity updated.');
    }

    public function destroy(Request $request, CareerOpportunity $careerOpportunity): RedirectResponse
    {
        $title = $careerOpportunity->title;
        $careerOpportunity->delete();
        app(ActivityLogger::class)->record($request, 'Career opportunity deleted', $title);

        return redirect()->route('admin.careers.index')->with('status', 'Career opportunity deleted.');
    }

    private function validateOpportunity(Request $request, bool $updating = false): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'in:Full-time,Part-time,Contract'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'application_email' => ['required', 'email', 'max:255'],
            'closes_at' => $updating
                ? ['nullable', 'date']
                : ['nullable', 'date', 'after_or_equal:today'],
        ]);
    }
}
