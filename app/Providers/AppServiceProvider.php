<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\SystemActivity;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('login', function (Request $request): array {
            $email = Str::lower(trim((string) $request->input('email', '')));
            $emailKey = hash('sha256', $email);

            return [
                Limit::perMinute(5)
                    ->by('login-email:'.$emailKey)
                    ->after(fn ($response): bool => $response->isRedirect()
                        && (bool) $request->attributes->get('login_failed', false)),
                Limit::perMinute(5)->by('login-ip:'.$request->ip()),
            ];
        });

        View::composer('partials.header', function (ViewInstance $view): void {
            $user = Auth::user();
            if (! $user) {
                return;
            }

            $unreadMessageCount = $user->is_admin
                ? ContactMessage::where('is_read', false)->count()
                : 0;
            $pendingCount = $user->is_admin
                ? User::where('is_approved', false)->count()
                : 0;
            $accountNotificationQuery = SystemActivity::query()
                ->where('user_id', $user->getKey())
                ->where(function ($query): void {
                    $query->where('action', 'like', '% approved by admin')
                        ->orWhere('action', 'like', '% rejected by admin');
                });
            $accountNotificationCount = $user->is_admin ? 0 : (clone $accountNotificationQuery)->count();
            $accountNotifications = $user->is_admin
                ? collect()
                : (clone $accountNotificationQuery)
                    ->latest('created_at')
                    ->latest('id')
                    ->limit(5)
                    ->get(['action', 'details', 'created_at', 'timezone'])
                    ->map(function (SystemActivity $activity): array {
                        $isRejected = str_ends_with($activity->action, 'rejected by admin');
                        $contentType = match (true) {
                            str_starts_with($activity->action, 'Gallery photo') => 'Photo',
                            str_starts_with($activity->action, 'Gallery video') => 'Video',
                            str_starts_with($activity->action, 'Post') => 'Post',
                            str_starts_with($activity->action, 'Hero image') => 'Hero image',
                            default => 'Submission',
                        };

                        return [
                            'title' => "{$contentType} ".($isRejected ? 'rejected' : 'approved'),
                            'details' => $activity->details,
                            'date' => $activity->created_at
                                ->copy()
                                ->setTimezone($activity->timezone)
                                ->format('M d, Y · h:i A'),
                            'icon' => $isRejected ? 'fa-solid fa-circle-xmark' : 'fa-solid fa-circle-check',
                            'url' => match ($contentType) {
                                'Photo' => route('account.content.photos'),
                                'Video' => route('account.content.videos'),
                                'Post' => route('account.content.posts'),
                                default => route('account.content.hero'),
                            },
                        ];
                    });
            $pendingSubmissionCounts = $user->is_admin
                ? [
                    'photos' => GalleryPhoto::query()->whereNotNull('user_id')->where('is_approved', false)->count(),
                    'videos' => GalleryVideo::query()->whereNotNull('user_id')->where('is_approved', false)->count(),
                    'posts' => Post::query()->whereNotNull('user_id')->where('is_approved', false)
                        ->whereIn('category', ['News', 'Event'])->count(),
                    'hero_images' => HeroSlide::query()
                        ->whereNotNull('user_id')
                        ->where('is_approved', false)
                        ->whereNull('rejected_at')
                        ->count(),
                ]
                : ['photos' => 0, 'videos' => 0, 'posts' => 0, 'hero_images' => 0];
            $pendingSubmissionCount = array_sum($pendingSubmissionCounts);

            $view->with([
                'unreadMessageCount' => $unreadMessageCount,
                'pendingCount' => $pendingCount,
                'pendingSubmissionCounts' => $pendingSubmissionCounts,
                'accountNotifications' => $accountNotifications,
                'notificationLabel' => $user->is_admin
                    ? 'Notifications, '.($unreadMessageCount + $pendingCount + $pendingSubmissionCount).' pending'
                    : 'Notifications, '.$accountNotificationCount.' submission updates',
                'notificationCount' => $user->is_admin
                    ? $unreadMessageCount + $pendingCount + $pendingSubmissionCount
                    : $accountNotificationCount,
            ]);
        });
    }
}
