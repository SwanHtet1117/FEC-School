<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        return view('admin.hero-management', [
            'slides' => HeroSlide::ordered()->get(),
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
        ]);

        return redirect()->route('admin.hero.manage')->with('status', 'Hero image added.');
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_text' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
        ]);

        $previousPath = $heroSlide->image_path;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hero-slides', 'public');
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

        if (isset($validated['image_path']) && $this->isUploadedImage($previousPath)) {
            $this->deleteUploadedImage($previousPath);
        }

        return redirect()->route('admin.hero.manage')->with('status', 'Hero image updated.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $imagePath = $heroSlide->image_path;
        $deleted = DB::transaction(function () use ($heroSlide): bool {
            $slides = HeroSlide::query()->lockForUpdate()->get();
            $slide = $slides->firstWhere('id', $heroSlide->getKey());

            if (! $slide || $slides->count() <= 1
                || ($slide->is_enabled && $slides->where('is_enabled', true)->count() <= 1)) {
                return false;
            }

            $slide->delete();

            return true;
        });

        if (! $deleted) {
            return redirect()->route('admin.hero.manage')
                ->withErrors(['hero_slide' => 'Keep at least one image and one enabled image in the slideshow.']);
        }

        if ($this->isUploadedImage($imagePath)) {
            $this->deleteUploadedImage($imagePath);
        }

        return redirect()->route('admin.hero.manage')->with('status', 'Hero image deleted.');
    }

    public function toggle(HeroSlide $heroSlide): RedirectResponse
    {
        $enabled = DB::transaction(function () use ($heroSlide): ?bool {
            $slides = HeroSlide::query()->lockForUpdate()->get();
            $slide = $slides->firstWhere('id', $heroSlide->getKey());

            if (! $slide || ($slide->is_enabled && $slides->where('is_enabled', true)->count() <= 1)) {
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

        return redirect()->route('admin.hero.manage')->with(
            'status',
            $enabled ? 'Hero image enabled.' : 'Hero image disabled.'
        );
    }

    private function isUploadedImage(string $path): bool
    {
        return str_starts_with($path, 'hero-slides/');
    }

    private function deleteUploadedImage(string $path): void
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new RuntimeException('The hero image file could not be removed from storage.');
        }
    }
}
