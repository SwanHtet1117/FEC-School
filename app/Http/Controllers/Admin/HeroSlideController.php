<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Services\ActivityLogger;
use App\Services\ModeratedContentStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        return view('admin.hero-management', [
            'slides' => HeroSlide::query()->with('user')->ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_text' => ['required', 'string', 'max:255'],
        ]);

        $path = $request->file('image')->store('hero-slides', 'public');
        if ($path === false) {
            throw new RuntimeException('The hero image could not be uploaded.');
        }

        HeroSlide::create([
            'image_path' => $path,
            'alt_text' => $validated['alt_text'],
            'sort_order' => (HeroSlide::max('sort_order') ?? -1) + 1,
            'is_enabled' => true,
            'is_approved' => true,
            'rejected_at' => null,
        ]);
        app(ActivityLogger::class)->record($request, 'Hero image added', $validated['alt_text']);

        return redirect()->route('admin.hero.manage')->with('status', 'Hero image added.');
    }

    public function update(
        Request $request,
        HeroSlide $heroSlide,
        ModeratedContentStorage $storage
    ): RedirectResponse {
        $validated = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_text' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
        ]);

        $previousPath = $heroSlide->image_path;
        if ($request->hasFile('image')) {
            $path = $heroSlide->is_approved
                ? $request->file('image')->store('hero-slides', 'public')
                : $storage->storePending($request->file('image'), 'hero-slides');
            if ($path === false) {
                throw new RuntimeException('The replacement hero image could not be uploaded.');
            }

            $validated['image_path'] = $path;
        }

        $heroSlide->update([
            'image_path' => $validated['image_path'] ?? $previousPath,
            'alt_text' => $validated['alt_text'],
            'sort_order' => $validated['sort_order'],
        ]);

        if (isset($validated['image_path'])) {
            $storage->delete($previousPath);
        }
        app(ActivityLogger::class)->record($request, 'Hero image updated', $validated['alt_text']);

        return redirect()->route('admin.hero.manage')->with('status', 'Hero image updated.');
    }

    public function destroy(
        Request $request,
        HeroSlide $heroSlide,
        ModeratedContentStorage $storage
    ): RedirectResponse {
        $imagePath = $heroSlide->image_path;
        $description = $heroSlide->alt_text;
        $deleted = DB::transaction(function () use ($heroSlide): bool {
            $slides = HeroSlide::query()->lockForUpdate()->get();
            $slide = $slides->firstWhere('id', $heroSlide->getKey());

            $approvedSlides = $slides->where('is_approved', true);
            if (! $slide || ($slide->is_approved && (
                $approvedSlides->count() <= 1
                || ($slide->is_enabled && $approvedSlides->where('is_enabled', true)->count() <= 1)
            ))) {
                return false;
            }

            $slide->delete();

            return true;
        });

        if (! $deleted) {
            return redirect()->route('admin.hero.manage')
                ->withErrors(['hero_slide' => 'Keep at least one image and one enabled image in the slideshow.']);
        }

        $storage->delete($imagePath);
        app(ActivityLogger::class)->record($request, 'Hero image deleted', $description);

        return redirect()->route('admin.hero.manage')->with('status', 'Hero image deleted.');
    }

    public function toggle(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        if (! $heroSlide->is_approved) {
            return redirect()->route('admin.hero.manage')
                ->withErrors(['hero_slide' => 'Approve this image before changing its homepage visibility.']);
        }

        $enabled = DB::transaction(function () use ($heroSlide): ?bool {
            $slides = HeroSlide::query()->lockForUpdate()->get();
            $slide = $slides->firstWhere('id', $heroSlide->getKey());

            if (! $slide || ($slide->is_enabled && $slides
                ->where('is_approved', true)
                ->where('is_enabled', true)
                ->count() <= 1)) {
                return null;
            }

            $slide->is_enabled = ! $slide->is_enabled;
            $slide->save();

            return $slide->is_enabled;
        });

        if ($enabled === null) {
            return redirect()->route('admin.hero.manage')
                ->withErrors(['hero_slide' => 'At least one hero image must remain enabled.']);
        }
        app(ActivityLogger::class)->record(
            $request,
            $enabled ? 'Hero image enabled' : 'Hero image disabled',
            $heroSlide->alt_text
        );

        return redirect()->route('admin.hero.manage')->with(
            'status',
            $enabled ? 'Hero image enabled.' : 'Hero image disabled.'
        );
    }

    public function approve(
        Request $request,
        HeroSlide $heroSlide,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        if ($heroSlide->is_approved) {
            return redirect()->route('admin.hero.manage')
                ->withErrors(['hero_slide' => 'This hero image has already been approved.']);
        }

        $previousPath = $heroSlide->image_path;
        $heroSlide->update([
            'image_path' => $storage->publishPending($previousPath, 'hero-slides'),
            'sort_order' => (HeroSlide::query()->where('is_approved', true)->max('sort_order') ?? -1) + 1,
            'is_enabled' => true,
            'is_approved' => true,
            'rejected_at' => null,
        ]);
        if ($previousPath !== $heroSlide->image_path) {
            $storage->delete($previousPath);
        }

        $activityLogger->record($request, 'Hero image approved', $heroSlide->alt_text);
        if ($heroSlide->user) {
            $activityLogger->record(
                $request,
                'Hero image approved by admin',
                $heroSlide->alt_text,
                user: $heroSlide->user
            );
        }

        return redirect()->route('admin.hero.manage')->with('status', 'Hero image approved and published.');
    }

    public function reject(
        Request $request,
        HeroSlide $heroSlide,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        if (! $heroSlide->user_id || $heroSlide->is_approved || $heroSlide->rejected_at) {
            return redirect()->route('admin.hero.manage')
                ->withErrors(['hero_slide' => 'Only submissions awaiting review can be rejected.']);
        }

        $heroSlide->update([
            'is_approved' => false,
            'is_enabled' => false,
            'rejected_at' => now(),
        ]);

        $activityLogger->record($request, 'Hero image rejected', $heroSlide->alt_text);
        if ($heroSlide->user) {
            $activityLogger->record(
                $request,
                'Hero image rejected by admin',
                $heroSlide->alt_text,
                user: $heroSlide->user
            );
        }

        return redirect()->route('admin.hero.manage')->with('status', 'Hero image rejected.');
    }
}
