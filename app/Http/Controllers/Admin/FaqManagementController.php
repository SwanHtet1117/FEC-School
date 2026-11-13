<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FaqManagementController extends Controller
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $filtersAreValid = $validator->passes();
        $filters = $filtersAreValid ? $validator->validated() : [];
        if ($filtersAreValid && array_key_exists('search', $filters)) {
            $filters['search'] = trim((string) ($filters['search'] ?? ''));
        }

        $faqsQuery = Faq::query();
        if (! $filtersAreValid) {
            $faqsQuery->whereRaw('1 = 0');
        } elseif (filled($filters['search'] ?? null)) {
            $search = $filters['search'];
            $faqsQuery->where(function (Builder $query) use ($search): void {
                $query->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        return view('admin.faq-management', [
            'faqs' => $faqsQuery->orderBy('sort_order')->orderBy('id')->get(),
            'faqCount' => Faq::query()->count(),
            'filters' => $filters,
            'filterErrors' => $validator->errors(),
            'hasFilters' => ! $filtersAreValid
                || collect($filters)->contains(fn ($value): bool => filled($value)),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFaq($request);
        $validated['sort_order'] = (int) Faq::query()->max('sort_order') + 1;

        Faq::query()->create($validated);

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ added.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faq-edit', [
            'faq' => $faq,
        ]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validateFaq($request));

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ updated.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ deleted.');
    }

    private function validateFaq(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:5000'],
        ]);
    }
}
