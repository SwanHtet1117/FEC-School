<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Services\ActivityLogger;
use App\Services\ModeratedContentStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AccountHeroSlideController extends Controller
{
    public function index(Request $request): View
    {
        return view('account.manage-hero-images', [
            'slides' => HeroSlide::query()
                ->where('user_id', $request->user()->getKey())
                ->ordered()
                ->get(),
        ]);
    }

    public function store(
        Request $request,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        $validated = $request->validate($this->rules(true));
        $path = $storage->storePending($validated['image'], 'hero-slides');

        $slide = HeroSlide::query()->create([
            'image_path' => $path,
            'alt_text' => $validated['alt_text'],
            'sort_order' => (HeroSlide::max('sort_order') ?? -1) + 1,
            'is_enabled' => false,
            'user_id' => $request->user()->getKey(),
            'is_approved' => false,
        ]);
        $activityLogger->record($request, 'Hero image submitted for review', $slide->alt_text);

        return redirect()->route('account.content.hero')
            ->with('status', 'Your hero image was submitted for admin review.');
    }

    public function update(
        Request $request,
        int $heroSlide,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        $slide = $this->ownedPendingSlide($request, $heroSlide);
        $validated = $request->validate($this->rules());
        $previousPath = $slide->image_path;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $storage->storePending($validated['image'], 'hero-slides');
            unset($validated['image']);
        }

        $slide->update([
            ...$validated,
            'is_approved' => false,
            'is_enabled' => false,
            'rejected_at' => null,
        ]);
        if (isset($validated['image_path'])) {
            $storage->delete($previousPath);
        }
        $activityLogger->record($request, 'Hero image submission updated', $slide->alt_text);

        return redirect()->route('account.content.hero')
            ->with('status', 'Your changes were submitted for admin review.');
    }

    public function destroy(
        Request $request,
        int $heroSlide,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        $slide = $this->ownedPendingSlide($request, $heroSlide);
        $path = $slide->image_path;
        $description = $slide->alt_text;
        $slide->delete();
        $storage->delete($path);
        $activityLogger->record($request, 'Hero image submission deleted', $description);

        return redirect()->route('account.content.hero')
            ->with('status', 'Your hero image submission was deleted.');
    }

    public function preview(Request $request, int $heroSlide)
    {
        $slide = HeroSlide::query()->findOrFail($heroSlide);
        abort_unless(
            $request->user()->is_admin || $slide->user_id === $request->user()->getKey(),
            404
        );
        abort_unless(str_starts_with($slide->image_path, 'pending-content/hero-slides/'), 404);

        return Storage::disk('local')->response($slide->image_path);
    }

    private function ownedPendingSlide(Request $request, int $heroSlide): HeroSlide
    {
        return HeroSlide::query()
            ->where('user_id', $request->user()->getKey())
            ->where('is_approved', false)
            ->findOrFail($heroSlide);
    }

    private function rules(bool $imageRequired = false): array
    {
        return [
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_text' => ['required', 'string', 'max:255'],
        ];
    }
}
