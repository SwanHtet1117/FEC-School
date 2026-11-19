<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Services\ActivityLogger;
use App\Services\ModeratedContentStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function update(
        Request $request,
        GalleryPhoto $galleryPhoto,
        ModeratedContentStorage $storage
    ): RedirectResponse {
        $validated = $request->validate($this->rules());
        $previousPath = $galleryPhoto->image_path;

        if ($request->hasFile('image')) {
            $path = $galleryPhoto->is_approved
                ? $request->file('image')->store('gallery-photos', 'public')
                : $storage->storePending($request->file('image'), 'photos');
            if ($path === false) {
                throw new RuntimeException('The replacement gallery photo could not be uploaded.');
            }
            $validated['image_path'] = $path;
        }

        $galleryPhoto->update($validated);
        if (isset($validated['image_path']) && $storage->isManagedUpload($previousPath)) {
            $storage->delete($previousPath);
        }
        app(ActivityLogger::class)->record($request, 'Gallery photo updated', $validated['title']);

        return redirect()->route('admin.gallery.photos.index')->with('status', 'Photo updated.');
    }

    public function approve(
        Request $request,
        GalleryPhoto $galleryPhoto,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        if ($galleryPhoto->is_approved) {
            return redirect()->route('admin.gallery.photos.index')
                ->withErrors(['photo' => 'This photo has already been approved.']);
        }

        $previousPath = $galleryPhoto->image_path;
        $galleryPhoto->update([
            'image_path' => $storage->publishPending($previousPath, 'gallery-photos'),
            'is_approved' => true,
        ]);
        if ($previousPath !== $galleryPhoto->image_path) {
            $storage->delete($previousPath);
        }

        $activityLogger->record($request, 'Gallery photo approved', $galleryPhoto->title);
        if ($galleryPhoto->user) {
            $activityLogger->record(
                $request,
                'Gallery photo approved by admin',
                $galleryPhoto->title,
                user: $galleryPhoto->user
            );
        }

        return redirect()->route('admin.gallery.photos.index')
            ->with('status', 'Photo approved and published.');
    }

    public function destroy(
        Request $request,
        GalleryPhoto $galleryPhoto,
        ModeratedContentStorage $storage
    ): RedirectResponse {
        $path = $galleryPhoto->image_path;
        $title = $galleryPhoto->title;
        $galleryPhoto->delete();
        $storage->delete($path);
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
}
