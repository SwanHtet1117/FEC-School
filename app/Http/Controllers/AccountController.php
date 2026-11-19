<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\SystemActivity;
use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $photoQuery = GalleryPhoto::query()->where('user_id', $user->getKey());
        $videoQuery = GalleryVideo::query()->where('user_id', $user->getKey());
        $postQuery = Post::query()->where('user_id', $user->getKey());
        $heroImageQuery = HeroSlide::query()->where('user_id', $user->getKey());

        $photoCount = (clone $photoQuery)->count();
        $photoPendingCount = (clone $photoQuery)->where('is_approved', false)->count();
        $photoApprovedCount = (clone $photoQuery)->where('is_approved', true)->count();
        $videoCount = (clone $videoQuery)->count();
        $videoPendingCount = (clone $videoQuery)->where('is_approved', false)->count();
        $videoApprovedCount = (clone $videoQuery)->where('is_approved', true)->count();
        $postCount = (clone $postQuery)->count();
        $postPendingCount = (clone $postQuery)->where('is_approved', false)->count();
        $postApprovedCount = (clone $postQuery)->where('is_approved', true)->count();
        $heroImageCount = (clone $heroImageQuery)->count();
        $heroImagePendingCount = (clone $heroImageQuery)->where('is_approved', false)->count();
        $heroImageApprovedCount = (clone $heroImageQuery)->where('is_approved', true)->count();

        $activities = SystemActivity::query()
            ->where('user_id', $user->getKey())
            ->whereDate('created_at', now()->toDateString())
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(10)
            ->get();
        $submissionUpdates = collect()
            ->concat(GalleryPhoto::query()
                ->where('user_id', $user->getKey())
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get()
                ->map(fn (GalleryPhoto $photo): array => [
                    'type' => 'Photo',
                    'title' => $photo->title,
                    'approved' => $photo->is_approved,
                    'updated_at' => $photo->updated_at,
                    'url' => route('account.content.photos'),
                    'icon' => 'fa-regular fa-images',
                ]))
            ->concat(GalleryVideo::query()
                ->where('user_id', $user->getKey())
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get()
                ->map(fn (GalleryVideo $video): array => [
                    'type' => 'Video',
                    'title' => $video->title,
                    'approved' => $video->is_approved,
                    'updated_at' => $video->updated_at,
                    'url' => route('account.content.videos'),
                    'icon' => 'fa-solid fa-circle-play',
                ]))
            ->concat(Post::query()
                ->where('user_id', $user->getKey())
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get()
                ->map(fn (Post $post): array => [
                    'type' => $post->category,
                    'title' => $post->title,
                    'approved' => $post->is_approved,
                    'updated_at' => $post->updated_at,
                    'url' => route('account.content.posts'),
                    'icon' => 'fa-regular fa-newspaper',
                ]))
            ->concat(HeroSlide::query()
                ->where('user_id', $user->getKey())
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get()
                ->map(fn (HeroSlide $slide): array => [
                    'type' => 'Hero image',
                    'title' => $slide->alt_text,
                    'approved' => $slide->is_approved,
                    'status' => $slide->is_approved
                        ? 'Approved'
                        : ($slide->rejected_at ? 'Rejected - revise and resubmit' : 'Awaiting review'),
                    'status_class' => $slide->is_approved
                        ? 'success'
                        : ($slide->rejected_at ? 'danger' : 'pending'),
                    'updated_at' => $slide->updated_at,
                    'url' => route('account.content.hero'),
                    'icon' => 'fa-regular fa-image',
                ]))
            ->sortByDesc('updated_at')
            ->take(10)
            ->values();

        $accountActivityDistribution = [
            ['label' => 'Photos', 'count' => $photoCount, 'color' => '#2563eb'],
            ['label' => 'Videos', 'count' => $videoCount, 'color' => '#ef4444'],
            ['label' => 'Posts & News', 'count' => $postCount, 'color' => '#f59e0b'],
            ['label' => 'Hero Images', 'count' => $heroImageCount, 'color' => '#10b981'],
        ];

        $totalAccountActivityCount = array_sum(array_column($accountActivityDistribution, 'count'));
        $chartStops = [];
        $chartStart = 0;
        foreach ($accountActivityDistribution as $index => $item) {
            $chartEnd = $totalAccountActivityCount > 0
                ? ($index === array_key_last($accountActivityDistribution)
                    ? 100
                    : $chartStart + ($item['count'] / $totalAccountActivityCount * 100))
                : 0;
            $chartStops[] = "{$item['color']} {$chartStart}% {$chartEnd}%";
            $chartStart = $chartEnd;
        }
        $accountActivityChartBackground = $totalAccountActivityCount > 0
            ? 'conic-gradient('.implode(', ', $chartStops).')'
            : '#e8edf4';

        $otherIssues = collect();
        if (! $user->is_approved) {
            $otherIssues->push([
                'label' => 'Account approval',
                'detail' => 'Awaiting administrator approval',
                'count' => 1,
                'url' => route('account.dashboard'),
            ]);
        }
        if (! $user->phone) {
            $otherIssues->push([
                'label' => 'Phone number',
                'detail' => 'No phone number is registered',
                'count' => 1,
                'url' => route('account.profile.edit'),
            ]);
        }
        foreach ([
            ['label' => 'Pending photos', 'detail' => 'Photos awaiting review', 'count' => $photoPendingCount, 'url' => route('account.content.photos')],
            ['label' => 'Pending videos', 'detail' => 'Videos awaiting review', 'count' => $videoPendingCount, 'url' => route('account.content.videos')],
            ['label' => 'Pending posts', 'detail' => 'News and events awaiting review', 'count' => $postPendingCount, 'url' => route('account.content.posts')],
            ['label' => 'Pending hero images', 'detail' => 'Hero images awaiting review', 'count' => $heroImagePendingCount, 'url' => route('account.content.hero')],
        ] as $issue) {
            if ($issue['count'] > 0) {
                $otherIssues->push($issue);
            }
        }

        return view('account.dashboard', [
            'user' => $user,
            'photoCount' => $photoCount,
            'photoPendingCount' => $photoPendingCount,
            'photoApprovedCount' => $photoApprovedCount,
            'videoCount' => $videoCount,
            'videoPendingCount' => $videoPendingCount,
            'videoApprovedCount' => $videoApprovedCount,
            'postCount' => $postCount,
            'postPendingCount' => $postPendingCount,
            'postApprovedCount' => $postApprovedCount,
            'heroImageCount' => $heroImageCount,
            'heroImagePendingCount' => $heroImagePendingCount,
            'heroImageApprovedCount' => $heroImageApprovedCount,
            'activities' => $activities,
            'activityCount' => SystemActivity::query()->where('user_id', $user->getKey())->count(),
            'submissionUpdates' => $submissionUpdates,
            'accountActivityDistribution' => $accountActivityDistribution,
            'totalAccountActivityCount' => $totalAccountActivityCount,
            'accountActivityChartBackground' => $accountActivityChartBackground,
            'otherIssues' => $otherIssues,
        ]);
    }

    public function activities(Request $request): View
    {
        $user = $request->user();
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'action' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:Success,Failed'],
            'device' => ['nullable', 'in:Desktop,Mobile,Tablet,Unknown'],
        ]);
        $filtersAreValid = $validator->passes();
        $filters = $filtersAreValid ? $validator->validated() : [];
        if ($filtersAreValid && array_key_exists('search', $filters)) {
            $filters['search'] = trim((string) ($filters['search'] ?? ''));
        }

        $activitiesQuery = SystemActivity::query()->where('user_id', $user->getKey());
        if (! $filtersAreValid) {
            $activitiesQuery->whereRaw('1 = 0');
        } elseif (filled($filters['search'] ?? null)) {
            $search = $filters['search'];
            $activitiesQuery->where(function (Builder $query) use ($search): void {
                $query->where('action', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%");
            });
        }
        if ($filtersAreValid && filled($filters['action'] ?? null)) {
            $activitiesQuery->where('action', $filters['action']);
        }
        if ($filtersAreValid && filled($filters['status'] ?? null)) {
            $activitiesQuery->where('status', $filters['status']);
        }
        if ($filtersAreValid && filled($filters['device'] ?? null)) {
            $activitiesQuery->where('device', $filters['device']);
        }

        $activities = $activitiesQuery
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(25)
            ->appends(array_filter($filters, fn ($value): bool => filled($value)));

        return view('account.activities', [
            'user' => $user,
            'activities' => $activities,
            'actions' => SystemActivity::query()
                ->where('user_id', $user->getKey())
                ->select('action')
                ->distinct()
                ->orderBy('action')
                ->pluck('action'),
            'filters' => $filters,
            'filterErrors' => $validator->errors(),
            'hasFilters' => ! $filtersAreValid
                || collect($filters)->contains(fn ($value): bool => filled($value)),
        ]);
    }

    public function editProfile(Request $request): View
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->getKey())],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($validated);
        app(ActivityLogger::class)->record($request, 'Profile updated', 'Account profile');

        return redirect()->route('account.profile.edit')->with('status', 'Profile updated.');
    }

    public function editSettings(): View
    {
        return view('account.settings');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update(['password' => $validated['password']]);
        app(ActivityLogger::class)->record($request, 'Password updated', 'Account password');

        return redirect()->route('account.settings.edit')->with('status', 'Password updated.');
    }
}
