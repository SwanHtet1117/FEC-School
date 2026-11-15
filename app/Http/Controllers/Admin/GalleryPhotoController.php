<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class GalleryPhotoController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery-photo-management', [
            'photos' => GalleryPhoto::ordered()->get(),
            'categories' => GalleryPhoto::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true));
        $path = $request->file('image')->store('gallery-photos', 'public');
        if ($path === false) {
            throw new RuntimeException('The gallery photo could not be uploaded.');
        }

        GalleryPhoto::create([
            ...$validated,
            'image_path' => $path,
            'views' => 0,
            'sort_order' => (GalleryPhoto::max('sort_order') ?? -1) + 1,
        ]);
        app(ActivityLogger::class)->record($request, 'Gallery photo added', $validated['title']);

        return redirect()->route('admin.gallery.photos.index')->with('status', 'Photo added to the gallery.');
    }

    public function update(Request $request, GalleryPhoto $galleryPhoto): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $previousPath = $galleryPhoto->image_path;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gallery-photos', 'public');
            if ($path === false) {
                throw new RuntimeException('The replacement gallery photo could not be uploaded.');
            }
            $validated['image_path'] = $path;
        }

        $galleryPhoto->update($validated);
        if (isset($validated['image_path']) && $this->isUploadedPhoto($previousPath)) {
            $this->deleteUploadedPhoto($previousPath);
        }
        app(ActivityLogger::class)->record($request, 'Gallery photo updated', $validated['title']);

        return redirect()->route('admin.gallery.photos.index')->with('status', 'Photo updated.');
    }

    public function destroy(Request $request, GalleryPhoto $galleryPhoto): RedirectResponse
    {
        $path = $galleryPhoto->image_path;
        $title = $galleryPhoto->title;
        $galleryPhoto->delete();

        if ($this->isUploadedPhoto($path)) {
            $this->deleteUploadedPhoto($path);
        }
        app(ActivityLogger::class)->record($request, 'Gallery photo deleted', $title);

        return redirect()->route('admin.gallery.photos.index')->with('status', 'Photo deleted.');
    }

    private function rules(bool $imageRequired = false): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', Rule::in(array_keys(GalleryPhoto::CATEGORIES))],
            'alt_text' => ['required', 'string', 'max:255'],
            'sort_order' => [$imageRequired ? 'nullable' : 'required', 'integer', 'min:0', 'max:10000'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function isUploadedPhoto(string $path): bool
    {
        return str_starts_with($path, 'gallery-photos/');
    }

    private function deleteUploadedPhoto(string $path): void
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new RuntimeException('The gallery photo file could not be removed from storage.');
        }
    }
}
