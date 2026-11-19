<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryVideo;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GalleryVideoController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery-video-management', [
            'videos' => GalleryVideo::ordered()->get(),
            'categories' => GalleryVideo::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true));

        GalleryVideo::create([
            ...$validated,
            'views' => 0,
            'sort_order' => (GalleryVideo::max('sort_order') ?? -1) + 1,
        ]);
        app(ActivityLogger::class)->record($request, 'Gallery video added', $validated['title']);

        return redirect()->route('admin.gallery.videos.index')->with('status', 'Video added to the gallery.');
    }

    public function update(Request $request, GalleryVideo $galleryVideo): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $galleryVideo->update($validated);
        app(ActivityLogger::class)->record($request, 'Gallery video updated', $validated['title']);

        return redirect()->route('admin.gallery.videos.index')->with('status', 'Video updated.');
    }

    public function approve(
        Request $request,
        GalleryVideo $galleryVideo,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        if ($galleryVideo->is_approved) {
            return redirect()->route('admin.gallery.videos.index')
                ->withErrors(['video' => 'This video has already been approved.']);
        }

        $galleryVideo->update(['is_approved' => true]);
        $activityLogger->record($request, 'Gallery video approved', $galleryVideo->title);
        if ($galleryVideo->user) {
            $activityLogger->record(
                $request,
                'Gallery video approved by admin',
                $galleryVideo->title,
                user: $galleryVideo->user
            );
        }

        return redirect()->route('admin.gallery.videos.index')
            ->with('status', 'Video approved and published.');
    }

    public function destroy(Request $request, GalleryVideo $galleryVideo): RedirectResponse
    {
        $title = $galleryVideo->title;
        $galleryVideo->delete();
        app(ActivityLogger::class)->record($request, 'Gallery video deleted', $title);

        return redirect()->route('admin.gallery.videos.index')->with('status', 'Video deleted.');
    }

    private function rules(bool $youtubeIdRequired = false): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', Rule::in(array_keys(GalleryVideo::CATEGORIES))],
            'youtube_id' => [
                $youtubeIdRequired ? 'required' : 'nullable',
                'string',
                'regex:/\A[A-Za-z0-9_-]{11}\z/',
            ],
            'duration' => ['required', 'string', 'max:30'],
            'sort_order' => [$youtubeIdRequired ? 'nullable' : 'required', 'integer', 'min:0', 'max:10000'],
        ];
    }
}
