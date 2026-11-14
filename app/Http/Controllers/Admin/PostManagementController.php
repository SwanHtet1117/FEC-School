<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class PostManagementController extends Controller
{
    private const CATEGORIES = ['News', 'Announcement', 'Event'];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'in:News,Announcement,Event'],
        ]);
        $postsQuery = Post::query();

        if (filled($filters['search'] ?? null)) {
            $search = trim($filters['search']);
            $postsQuery->where(function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if (filled($filters['category'] ?? null)) {
            $postsQuery->where('category', $filters['category']);
        }

        return view('admin.post-management', [
            'posts' => $postsQuery->latest('published_at')->paginate(15)->withQueryString(),
            'postCount' => Post::query()->count(),
            'categoryCounts' => Post::query()
                ->selectRaw('category, count(*) as total')
                ->groupBy('category')
                ->pluck('total', 'category'),
            'filters' => $filters,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);
        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['image_path'] = $this->storeImage($request) ?? 'fec_building.jpg';
        unset($validated['image']);

        $post = Post::query()->create($validated);
        app(ActivityLogger::class)->record($request, 'Post added', $post->title);

        return redirect()->route('admin.posts.index')->with('status', 'Post published.');
    }

    public function edit(Post $post): View
    {
        return view('admin.post-edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $this->validatePost($request);
        $previousImagePath = $post->image_path;
        $imagePath = $this->storeImage($request);
        $validated['slug'] = $this->uniqueSlug($validated['title'], $post);
        $validated['image_path'] = $imagePath ?? $previousImagePath;
        unset($validated['image']);

        $post->update($validated);
        if ($imagePath !== null && str_starts_with($previousImagePath, 'posts/')) {
            $this->deleteUploadedImage($previousImagePath);
        }

        app(ActivityLogger::class)->record($request, 'Post updated', $post->title);

        return redirect()->route('admin.posts.index')->with('status', 'Post updated.');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        $imagePath = $post->image_path;
        $title = $post->title;
        $post->delete();

        if (str_starts_with($imagePath, 'posts/')) {
            $this->deleteUploadedImage($imagePath);
        }
        app(ActivityLogger::class)->record($request, 'Post deleted', $title);

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted.');
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:'.implode(',', self::CATEGORIES)],
            'published_at' => ['required', 'date'],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:20000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_text' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = $request->file('image')->store('posts', 'public');
        if ($path === false) {
            throw new RuntimeException('The post image could not be uploaded.');
        }

        return $path;
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

    private function deleteUploadedImage(string $path): void
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new RuntimeException('The post image could not be removed from storage.');
        }
    }
}
