<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\Post;
use App\Services\ActivityLogger;
use App\Services\ModeratedContentStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class AccountContentController extends Controller
{
    public function photos(Request $request): View
    {
        return view('account.manage-photos', [
            'photos' => GalleryPhoto::query()
                ->where('user_id', $request->user()->getKey())
                ->ordered()
                ->get(),
            'categories' => GalleryPhoto::CATEGORIES,
        ]);
    }

    public function storePhoto(Request $request, ModeratedContentStorage $storage, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate($this->photoRules(true));
        $imagePath = $storage->storePending($validated['image'], 'photos');
        unset($validated['image']);

        $photo = GalleryPhoto::query()->create([
            ...$validated,
            'image_path' => $imagePath,
            'user_id' => $request->user()->getKey(),
            'is_approved' => false,
            'views' => 0,
            'sort_order' => (GalleryPhoto::max('sort_order') ?? -1) + 1,
        ]);
        $activityLogger->record($request, 'Photo submitted for review', $photo->title);

        return redirect()->route('account.content.photos')
            ->with('status', 'Your photo was submitted for admin review.');
    }

    public function updatePhoto(
        Request $request,
        int $photo,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        $photo = $this->ownedPhoto($request, $photo);
        $validated = $request->validate($this->photoRules());
        $previousPath = $photo->image_path;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $storage->storePending($validated['image'], 'photos');
            unset($validated['image']);
        }

        $photo->update([
            ...$validated,
            'is_approved' => false,
        ]);

        if (isset($validated['image_path']) && $storage->isManagedUpload($previousPath)) {
            $storage->delete($previousPath);
        }

        $activityLogger->record($request, 'Photo updated for review', $photo->title);

        return redirect()->route('account.content.photos')
            ->with('status', 'Your changes were submitted for admin review.');
    }

    public function destroyPhoto(
        Request $request,
        int $photo,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        $photo = $this->ownedPhoto($request, $photo);
        $path = $photo->image_path;
        $title = $photo->title;
        $photo->delete();
        $storage->delete($path);
        $activityLogger->record($request, 'Photo submission deleted', $title);

        return redirect()->route('account.content.photos')->with('status', 'Your photo submission was deleted.');
    }

    public function photoPreview(Request $request, int $photo)
    {
        $photo = GalleryPhoto::query()->findOrFail($photo);
        abort_unless(
            $request->user()->is_admin || $photo->user_id === $request->user()->getKey(),
            404
        );
        abort_unless(str_starts_with($photo->image_path, 'pending-content/photos/'), 404);

        return Storage::disk('local')->response($photo->image_path);
    }

    public function videos(Request $request): View
    {
        return view('account.manage-videos', [
            'videos' => GalleryVideo::query()
                ->where('user_id', $request->user()->getKey())
                ->ordered()
                ->get(),
            'categories' => GalleryVideo::CATEGORIES,
        ]);
    }

    public function storeVideo(Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $validated = $request->validate($this->videoRules());
        $video = GalleryVideo::query()->create([
            ...$validated,
            'user_id' => $request->user()->getKey(),
            'is_approved' => false,
            'views' => 0,
            'sort_order' => (GalleryVideo::max('sort_order') ?? -1) + 1,
        ]);
        $activityLogger->record($request, 'Video submitted for review', $video->title);

        return redirect()->route('account.content.videos')
            ->with('status', 'Your video was submitted for admin review.');
    }

    public function updateVideo(Request $request, int $video, ActivityLogger $activityLogger): RedirectResponse
    {
        $video = GalleryVideo::query()
            ->where('user_id', $request->user()->getKey())
            ->findOrFail($video);
        $validated = $request->validate($this->videoRules());

        $video->update([...$validated, 'is_approved' => false]);
        $activityLogger->record($request, 'Video updated for review', $video->title);

        return redirect()->route('account.content.videos')
            ->with('status', 'Your changes were submitted for admin review.');
    }

    public function destroyVideo(Request $request, int $video, ActivityLogger $activityLogger): RedirectResponse
    {
        $video = GalleryVideo::query()
            ->where('user_id', $request->user()->getKey())
            ->findOrFail($video);
        $title = $video->title;
        $video->delete();
        $activityLogger->record($request, 'Video submission deleted', $title);

        return redirect()->route('account.content.videos')->with('status', 'Your video submission was deleted.');
    }

    public function posts(Request $request): View
    {
        return view('account.manage-news-events', [
            'posts' => Post::query()
                ->where('user_id', $request->user()->getKey())
                ->latest('published_at')
                ->get(),
        ]);
    }

    public function storePost(
        Request $request,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        $validated = $request->validate($this->postRules());
        $imagePath = $request->hasFile('image')
            ? $storage->storePending($validated['image'], 'posts')
            : 'fec_building.jpg';
        unset($validated['image']);

        $post = Post::query()->create([
            ...$validated,
            'slug' => $this->uniqueSlug($validated['title']),
            'image_path' => $imagePath,
            'department' => null,
            'user_id' => $request->user()->getKey(),
            'is_approved' => false,
        ]);
        $activityLogger->record($request, 'News or event submitted for review', $post->title);

        return redirect()->route('account.content.posts')
            ->with('status', 'Your News/Event post was submitted for admin review.');
    }

    public function updatePost(
        Request $request,
        int $post,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        $post = Post::query()
            ->where('user_id', $request->user()->getKey())
            ->findOrFail($post);
        $validated = $request->validate($this->postRules());
        $previousPath = $post->image_path;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $storage->storePending($validated['image'], 'posts');
            unset($validated['image']);
        }

        $validated['slug'] = $this->uniqueSlug($validated['title'], $post);
        $validated['department'] = null;
        $validated['is_approved'] = false;
        $post->update($validated);

        if (isset($validated['image_path']) && $storage->isManagedUpload($previousPath)) {
            $storage->delete($previousPath);
        }

        $activityLogger->record($request, 'News or event updated for review', $post->title);

        return redirect()->route('account.content.posts')
            ->with('status', 'Your changes were submitted for admin review.');
    }

    public function destroyPost(
        Request $request,
        int $post,
        ModeratedContentStorage $storage,
        ActivityLogger $activityLogger
    ): RedirectResponse {
        $post = Post::query()
            ->where('user_id', $request->user()->getKey())
            ->findOrFail($post);
        $path = $post->image_path;
        $title = $post->title;
        $post->delete();
        $storage->delete($path);
        $activityLogger->record($request, 'News or event submission deleted', $title);

        return redirect()->route('account.content.posts')->with('status', 'Your post submission was deleted.');
    }

    public function postPreview(Request $request, int $post)
    {
        $post = Post::query()->findOrFail($post);
        abort_unless(
            $request->user()->is_admin || $post->user_id === $request->user()->getKey(),
            404
        );
        abort_unless(str_starts_with($post->image_path, 'pending-content/posts/'), 404);

        return Storage::disk('local')->response($post->image_path);
    }

    private function ownedPhoto(Request $request, int $photo): GalleryPhoto
    {
        return GalleryPhoto::query()
            ->where('user_id', $request->user()->getKey())
            ->findOrFail($photo);
    }

    private function photoRules(bool $imageRequired = false): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', Rule::in(array_keys(GalleryPhoto::CATEGORIES))],
            'alt_text' => ['required', 'string', 'max:255'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function videoRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', Rule::in(array_keys(GalleryVideo::CATEGORIES))],
            'youtube_id' => ['required', 'string', 'regex:/\A[A-Za-z0-9_-]{11}\z/'],
            'duration' => ['required', 'string', 'max:30'],
        ];
    }

    private function postRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:News,Event'],
            'published_at' => ['required', 'date'],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:20000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_text' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function uniqueSlug(string $title, ?Post $post = null): string
    {
        $baseSlug = Str::slug($title) ?: 'post';
        $slug = $baseSlug;
        $suffix = 2;

        while (true) {
            $query = Post::query()->where('slug', $slug);
            if ($post) {
                $query->where('id', '!=', $post->getKey());
            }

            if (! $query->exists()) {
                return $slug;
            }

            $slug = $baseSlug.'-'.$suffix++;
        }
    }
}
