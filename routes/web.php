<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountContentController;
use App\Http\Controllers\AccountHeroSlideController;
use App\Http\Controllers\Admin\CareerOpportunityController;
use App\Http\Controllers\Admin\ContactMessageManagementController;
use App\Http\Controllers\Admin\EventCalendarController;
use App\Http\Controllers\Admin\FaqManagementController;
use App\Http\Controllers\Admin\GalleryPhotoController;
use App\Http\Controllers\Admin\GalleryVideoController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\PostManagementController;
use App\Http\Controllers\Admin\SystemActivityController;
use App\Http\Controllers\Admin\TestimonialManagementController;
use App\Http\Controllers\Admin\UserContentRequestController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LoginOtpController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\GalleryPhotoViewController;
use App\Http\Controllers\GalleryVideoViewController;
use App\Http\Controllers\PostController;
use App\Models\CareerOpportunity;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\SystemActivity;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main.home', [
        'heroSlides' => HeroSlide::query()
            ->where('is_approved', true)
            ->where('is_enabled', true)
            ->ordered()
            ->get(),
        'testimonials' => Testimonial::query()->orderBy('sort_order')->orderBy('id')->get(),
        'latestPosts' => Post::query()
            ->where('is_approved', true)
            ->whereIn('category', ['News', 'Event'])
            ->latest('published_at')
            ->latest('id')
            ->limit(3)
            ->get(),
    ]);
})->name('home');

Route::get('/about', function () {
    return view('main.about');
});

Route::get('/contact', function () {
    return view('main.contact', [
        'careerOpportunities' => CareerOpportunity::open()->latest()->get(),
    ]);
})->name('contact');

Route::post('/contact', [ContactMessageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.messages.store');

Route::get('/news-events', [PostController::class, 'newsEvents'])->name('news-events.index');

Route::get('/campus-life', function () {
    return view('main.campus-life', [
        'campusGalleryPhotos' => GalleryPhoto::query()
            ->where('is_approved', true)
            ->where('category', 'campus-life')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(8)
            ->get(),
    ]);
});

Route::get('/academics', function () {
    return view('main.academic-overview');
});

Route::get('/academics/early-years', function () {
    return view('main.early-years');
});

Route::get('/academics/primary', function () {
    return view('main.primary-school');
});

Route::get('/academics/secondary', function () {
    return view('main.secondary-school');
});

Route::get('/academics/ged-igcse', function () {
    return view('main.ged-igcse');
});

Route::get('/gallery/photo', function () {
    return view('main.photo-gallery', [
        'galleryPhotos' => GalleryPhoto::query()
            ->where('is_approved', true)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get(),
    ]);
});

Route::post('/gallery/photos/{galleryPhoto}/view', GalleryPhotoViewController::class)
    ->middleware('throttle:120,1')
    ->name('gallery.photos.view');

Route::get('/gallery/video', function () {
    return view('main.video-gallery', [
        'videos' => GalleryVideo::query()
            ->where('is_approved', true)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get(),
    ]);
});

Route::post('/gallery/videos/{galleryVideo}/view', GalleryVideoViewController::class)
    ->middleware('throttle:120,1')
    ->name('gallery.videos.view');

Route::get('/admissions', function () {
    return view('main.admission-overview', [
        'faqs' => Faq::query()->orderBy('sort_order')->orderBy('id')->get(),
    ]);
});

Route::get('/news-events/all-posts', [PostController::class, 'all'])->name('news-events.all');

Route::get('/news-events/posts/{slug}', [PostController::class, 'show'])->name('news-events.posts.show');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:login')->name('login.store');
Route::get('/login/otp', [LoginOtpController::class, 'show'])->name('login.otp');
Route::post('/login/otp', [LoginOtpController::class, 'verify'])
    ->middleware('throttle:6,1')
    ->name('login.otp.verify');
Route::post('/login/otp/resend', [LoginOtpController::class, 'resend'])
    ->middleware('throttle:3,1')
    ->name('login.otp.resend');
Route::get('/forgot-password', [PasswordResetOtpController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetOtpController::class, 'send'])
    ->middleware('throttle:5,1')
    ->name('password.email');
Route::get('/forgot-password/otp', [PasswordResetOtpController::class, 'showOtp'])->name('password.otp');
Route::post('/forgot-password/otp', [PasswordResetOtpController::class, 'verify'])
    ->middleware('throttle:6,1')
    ->name('password.otp.verify');
Route::post('/forgot-password/otp/resend', [PasswordResetOtpController::class, 'resend'])
    ->middleware('throttle:3,1')
    ->name('password.otp.resend');
Route::get('/reset-password', [PasswordResetOtpController::class, 'showReset'])->name('password.reset');
Route::put('/reset-password', [PasswordResetOtpController::class, 'update'])
    ->middleware('throttle:5,1')
    ->name('password.update');
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

Route::middleware('auth')->group(function () {
    Route::get('/account/dashboard', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/account/activities', [AccountController::class, 'activities'])
        ->name('account.activities.index');
    Route::get('/account/content/hero-images', [AccountHeroSlideController::class, 'index'])
        ->name('account.content.hero');
    Route::post('/account/content/hero-images', [AccountHeroSlideController::class, 'store'])
        ->name('account.content.hero.store');
    Route::get('/account/content/hero-images/{heroSlide}/preview', [AccountHeroSlideController::class, 'preview'])
        ->name('account.content.hero.preview');
    Route::put('/account/content/hero-images/{heroSlide}', [AccountHeroSlideController::class, 'update'])
        ->name('account.content.hero.update');
    Route::delete('/account/content/hero-images/{heroSlide}', [AccountHeroSlideController::class, 'destroy'])
        ->name('account.content.hero.destroy');
    Route::get('/account/content/photos', [AccountContentController::class, 'photos'])->name('account.content.photos');
    Route::post('/account/content/photos', [AccountContentController::class, 'storePhoto'])
        ->name('account.content.photos.store');
    Route::get('/account/content/photos/{photo}/preview', [AccountContentController::class, 'photoPreview'])
        ->name('account.content.photos.preview');
    Route::put('/account/content/photos/{photo}', [AccountContentController::class, 'updatePhoto'])
        ->name('account.content.photos.update');
    Route::delete('/account/content/photos/{photo}', [AccountContentController::class, 'destroyPhoto'])
        ->name('account.content.photos.destroy');
    Route::get('/account/content/videos', [AccountContentController::class, 'videos'])->name('account.content.videos');
    Route::post('/account/content/videos', [AccountContentController::class, 'storeVideo'])
        ->name('account.content.videos.store');
    Route::put('/account/content/videos/{video}', [AccountContentController::class, 'updateVideo'])
        ->name('account.content.videos.update');
    Route::delete('/account/content/videos/{video}', [AccountContentController::class, 'destroyVideo'])
        ->name('account.content.videos.destroy');
    Route::get('/account/content/news-events', [AccountContentController::class, 'posts'])
        ->name('account.content.posts');
    Route::post('/account/content/news-events', [AccountContentController::class, 'storePost'])
        ->name('account.content.posts.store');
    Route::get('/account/content/news-events/{post}/preview', [AccountContentController::class, 'postPreview'])
        ->name('account.content.posts.preview');
    Route::put('/account/content/news-events/{post}', [AccountContentController::class, 'updatePost'])
        ->name('account.content.posts.update');
    Route::delete('/account/content/news-events/{post}', [AccountContentController::class, 'destroyPost'])
        ->name('account.content.posts.destroy');
    Route::get('/account/profile', [AccountController::class, 'editProfile'])->name('account.profile.edit');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/settings', [AccountController::class, 'editSettings'])->name('account.settings.edit');
    Route::put('/account/settings/password', [AccountController::class, 'updatePassword'])->name('account.settings.update');
});

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('register.store');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $heroImageCount = HeroSlide::count();
        $pendingHeroApprovalCount = HeroSlide::query()
            ->whereNotNull('user_id')
            ->where('is_approved', false)
            ->whereNull('rejected_at')
            ->count();
        $approvedHeroCount = HeroSlide::query()->where('is_approved', true)->count();
        $galleryPhotoCount = GalleryPhoto::count();
        $approvedPhotoCount = GalleryPhoto::query()->where('is_approved', true)->count();
        $galleryVideoCount = GalleryVideo::count();
        $approvedVideoCount = GalleryVideo::query()->where('is_approved', true)->count();
        $pendingPhotoApprovalCount = GalleryPhoto::query()
            ->whereNotNull('user_id')
            ->where('is_approved', false)
            ->count();
        $pendingVideoApprovalCount = GalleryVideo::query()
            ->whereNotNull('user_id')
            ->where('is_approved', false)
            ->count();
        $messageCount = ContactMessage::count();
        $pendingCount = User::where('is_approved', false)->count();
        $careerCount = CareerOpportunity::open()->count();
        $totalCareerCount = CareerOpportunity::count();
        $postCount = Post::query()->count();
        $pendingPostApprovalCount = Post::query()
            ->whereNotNull('user_id')
            ->where('is_approved', false)
            ->whereIn('category', ['News', 'Event'])
            ->count();
        $approvedPostCount = Post::query()->where('is_approved', true)->count();
        $eventPostCount = Post::query()->where('category', 'Event')->count();
        $faqCount = Faq::count();
        $testimonialCount = Testimonial::count();
        $contentDistribution = [
            ['label' => 'Hero Images', 'count' => $heroImageCount, 'color' => '#2563eb'],
            ['label' => 'News, Announcements & Events', 'count' => $postCount, 'color' => '#f59e0b'],
            ['label' => 'Photo Gallery', 'count' => $galleryPhotoCount, 'color' => '#10b981'],
            ['label' => 'Video Gallery', 'count' => $galleryVideoCount, 'color' => '#ef4444'],
            ['label' => 'FAQs', 'count' => $faqCount, 'color' => '#8b5cf6'],
            ['label' => 'Contact Messages', 'count' => $messageCount, 'color' => '#06b6d4'],
            ['label' => 'Career Opportunities', 'count' => $totalCareerCount, 'color' => '#f97316'],
            ['label' => 'Testimonials', 'count' => $testimonialCount, 'color' => '#64748b'],
        ];
        $totalContentCount = array_sum(array_column($contentDistribution, 'count'));
        $chartStops = [];
        $chartStart = 0;
        foreach ($contentDistribution as $index => $item) {
            $chartEnd = $totalContentCount > 0
                ? ($index === array_key_last($contentDistribution)
                    ? 100
                    : $chartStart + ($item['count'] / $totalContentCount * 100))
                : 0;
            $chartStops[] = "{$item['color']} {$chartStart}% {$chartEnd}%";
            $chartStart = $chartEnd;
        }
        $chartBackground = $totalContentCount > 0
            ? 'conic-gradient('.implode(', ', $chartStops).')'
            : '#e8edf4';

        $activityChartEnd = now()->endOfDay();
        $activityChartStart = $activityChartEnd->copy()->startOfDay()->subDays(29);
        $failedActivitiesQuery = SystemActivity::query()
            ->where('status', 'Failed')
            ->whereBetween('created_at', [$activityChartStart, $activityChartEnd]);
        $failedActivityCount = (clone $failedActivitiesQuery)->count();
        $failedActivities = (clone $failedActivitiesQuery)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get(['action', 'details', 'user_name', 'created_at', 'timezone']);
        $activityChartCategories = [
            'photo_gallery' => 'Photo gallery',
            'video_gallery' => 'Video gallery',
            'posts_content' => 'Posts & content',
            'accounts' => 'Accounts',
            'other' => 'Other',
        ];
        $activitiesByDate = SystemActivity::query()
            ->whereBetween('created_at', [$activityChartStart, $activityChartEnd])
            ->get(['created_at', 'action'])
            ->groupBy(fn (SystemActivity $activity): string => $activity->created_at->toDateString());
        $userActivityChart = [];

        for ($day = 0; $day < 30; $day++) {
            $date = $activityChartStart->copy()->addDays($day);
            $activitiesForDay = $activitiesByDate->get($date->toDateString(), collect());
            $activityCounts = array_fill_keys(array_keys($activityChartCategories), 0);

            foreach ($activitiesForDay as $activity) {
                $category = match (true) {
                    str_starts_with($activity->action, 'Gallery photo ') => 'photo_gallery',
                    str_starts_with($activity->action, 'Gallery video ') => 'video_gallery',
                    str_starts_with($activity->action, 'Post '),
                    str_starts_with($activity->action, 'Hero image '),
                    str_starts_with($activity->action, 'Career opportunity '),
                    str_starts_with($activity->action, 'Parent recommendation '),
                    str_starts_with($activity->action, 'FAQ ') => 'posts_content',
                    in_array($activity->action, [
                        'Login',
                        'Logout',
                        'Registration',
                        'Email verified',
                        'User approved',
                        'User deleted',
                        'Profile updated',
                        'Password updated',
                    ], true) => 'accounts',
                    default => 'other',
                };
                $activityCounts[$category]++;
            }

            $userActivityChart[] = [
                'date' => $date->toDateString(),
                'label' => $date->format('M j'),
                'counts' => $activityCounts,
            ];
        }

        $activityChartMax = max(1, max(array_map(
            static fn (array $day): int => max($day['counts']),
            $userActivityChart
        )));
        $activityChartWidth = 720;
        $activityChartHeight = 260;
        $activityChartLeft = 48;
        $activityChartTop = 18;
        $activityChartPlotWidth = $activityChartWidth - $activityChartLeft - 18;
        $activityChartPlotHeight = $activityChartHeight - $activityChartTop - 38;
        $activityChartSeries = [];
        $activityChartXLabels = [];

        foreach ($activityChartCategories as $key => $label) {
            $points = [];
            $total = 0;

            foreach ($userActivityChart as $index => &$day) {
                $x = round($activityChartLeft + ($index / 29 * $activityChartPlotWidth), 2);
                $value = $day['counts'][$key];
                $y = round(
                    $activityChartTop + $activityChartPlotHeight - ($value / $activityChartMax * $activityChartPlotHeight),
                    2
                );
                $points[] = "{$x},{$y}";
                $total += $value;

                if ($key === array_key_first($activityChartCategories) && ($index % 6 === 0 || $index === 29)) {
                    $activityChartXLabels[] = ['x' => $x, 'label' => $day['label']];
                }
            }
            unset($day);

            $activityChartSeries[] = [
                'key' => $key,
                'label' => $label,
                'total' => $total,
                'points' => implode(' ', $points),
            ];
        }

        $activityChartGridLines = [];
        foreach (array_unique([0, (int) ceil($activityChartMax / 2), $activityChartMax]) as $value) {
            $activityChartGridLines[] = [
                'value' => $value,
                'y' => round($activityChartTop + $activityChartPlotHeight - ($value / $activityChartMax * $activityChartPlotHeight), 2),
            ];
        }

        return view('admin.dashboard', [
            'heroImageCount' => $heroImageCount,
            'galleryPhotoCount' => $galleryPhotoCount,
            'galleryVideoCount' => $galleryVideoCount,
            'pendingPhotoApprovalCount' => $pendingPhotoApprovalCount,
            'approvedPhotoCount' => $approvedPhotoCount,
            'pendingHeroApprovalCount' => $pendingHeroApprovalCount,
            'approvedHeroCount' => $approvedHeroCount,
            'pendingVideoApprovalCount' => $pendingVideoApprovalCount,
            'approvedVideoCount' => $approvedVideoCount,
            'pendingPostApprovalCount' => $pendingPostApprovalCount,
            'approvedPostCount' => $approvedPostCount,
            'activities' => SystemActivity::latest()->limit(10)->get(),
            'messageCount' => $messageCount,
            'careerCount' => $careerCount,
            'postCount' => $postCount,
            'eventPostCount' => $eventPostCount,
            'contentDistribution' => $contentDistribution,
            'totalContentCount' => $totalContentCount,
            'chartBackground' => $chartBackground,
            'activityChartSeries' => $activityChartSeries,
            'activityChartGridLines' => $activityChartGridLines,
            'activityChartXLabels' => $activityChartXLabels,
            'failedActivityCount' => $failedActivityCount,
            'failedActivities' => $failedActivities,
            'faqCount' => $faqCount,
            'testimonialCount' => $testimonialCount,
            'userCount' => User::count(),
            'pendingCount' => $pendingCount,
            'adminCount' => User::where('is_admin', true)->count(),
        ]);
    })->name('admin.dashboard');

    Route::get('/activities', [SystemActivityController::class, 'index'])
        ->name('admin.activities.index');

    Route::get('/requests', [UserContentRequestController::class, 'index'])
        ->name('admin.requests.index');

    Route::get('/events/calendar', [EventCalendarController::class, 'index'])
        ->name('admin.events.calendar');

    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/users/{user}/approve', [UserManagementController::class, 'approve'])
        ->name('admin.users.approve');
    Route::post('/users/{user}/promote', [UserManagementController::class, 'promote'])
        ->name('admin.users.promote');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])
        ->name('admin.users.destroy');

    Route::get('/messages', [ContactMessageManagementController::class, 'index'])
        ->name('admin.messages.index');
    Route::post('/messages/{contactMessage}/toggle-read', [ContactMessageManagementController::class, 'toggleRead'])
        ->name('admin.messages.toggle-read');
    Route::delete('/messages/{contactMessage}', [ContactMessageManagementController::class, 'destroy'])
        ->name('admin.messages.destroy');

    Route::get('/careers', [CareerOpportunityController::class, 'index'])->name('admin.careers.index');
    Route::post('/careers', [CareerOpportunityController::class, 'store'])->name('admin.careers.store');
    Route::get('/careers/{careerOpportunity}/edit', [CareerOpportunityController::class, 'edit'])
        ->name('admin.careers.edit');
    Route::put('/careers/{careerOpportunity}', [CareerOpportunityController::class, 'update'])
        ->name('admin.careers.update');
    Route::delete('/careers/{careerOpportunity}', [CareerOpportunityController::class, 'destroy'])
        ->name('admin.careers.destroy');

    Route::get('/posts', [PostManagementController::class, 'index'])->name('admin.posts.index');
    Route::post('/posts', [PostManagementController::class, 'store'])->name('admin.posts.store');
    Route::get('/posts/{post}/edit', [PostManagementController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/posts/{post}', [PostManagementController::class, 'update'])->name('admin.posts.update');
    Route::post('/posts/{post}/approve', [PostManagementController::class, 'approve'])
        ->name('admin.posts.approve');
    Route::delete('/posts/{post}', [PostManagementController::class, 'destroy'])->name('admin.posts.destroy');

    Route::get('/faqs', [FaqManagementController::class, 'index'])->name('admin.faqs.index');
    Route::post('/faqs', [FaqManagementController::class, 'store'])->name('admin.faqs.store');
    Route::get('/faqs/{faq}/edit', [FaqManagementController::class, 'edit'])->name('admin.faqs.edit');
    Route::put('/faqs/{faq}', [FaqManagementController::class, 'update'])->name('admin.faqs.update');
    Route::delete('/faqs/{faq}', [FaqManagementController::class, 'destroy'])->name('admin.faqs.destroy');

    Route::get('/testimonials', [TestimonialManagementController::class, 'index'])
        ->name('admin.testimonials.index');
    Route::post('/testimonials', [TestimonialManagementController::class, 'store'])
        ->name('admin.testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialManagementController::class, 'edit'])
        ->name('admin.testimonials.edit');
    Route::put('/testimonials/{testimonial}', [TestimonialManagementController::class, 'update'])
        ->name('admin.testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialManagementController::class, 'destroy'])
        ->name('admin.testimonials.destroy');

    Route::get('/hero/manage', [HeroSlideController::class, 'index'])->name('admin.hero.manage');
    Route::post('/hero/manage', [HeroSlideController::class, 'store'])->name('admin.hero.store');
    Route::put('/hero/manage/{heroSlide}', [HeroSlideController::class, 'update'])
        ->name('admin.hero.update');
    Route::delete('/hero/manage/{heroSlide}', [HeroSlideController::class, 'destroy'])
        ->name('admin.hero.destroy');
    Route::post('/hero/manage/{heroSlide}/toggle', [HeroSlideController::class, 'toggle'])
        ->name('admin.hero.toggle');
    Route::post('/hero/manage/{heroSlide}/approve', [HeroSlideController::class, 'approve'])
        ->name('admin.hero.approve');
    Route::post('/hero/manage/{heroSlide}/reject', [HeroSlideController::class, 'reject'])
        ->name('admin.hero.reject');

    Route::get('/gallery/photos', [GalleryPhotoController::class, 'index'])
        ->name('admin.gallery.photos.index');
    Route::post('/gallery/photos', [GalleryPhotoController::class, 'store'])
        ->name('admin.gallery.photos.store');
    Route::put('/gallery/photos/{galleryPhoto}', [GalleryPhotoController::class, 'update'])
        ->name('admin.gallery.photos.update');
    Route::post('/gallery/photos/{galleryPhoto}/approve', [GalleryPhotoController::class, 'approve'])
        ->name('admin.gallery.photos.approve');
    Route::delete('/gallery/photos/{galleryPhoto}', [GalleryPhotoController::class, 'destroy'])
        ->name('admin.gallery.photos.destroy');

    Route::get('/gallery/videos', [GalleryVideoController::class, 'index'])
        ->name('admin.gallery.videos.index');
    Route::post('/gallery/videos', [GalleryVideoController::class, 'store'])
        ->name('admin.gallery.videos.store');
    Route::put('/gallery/videos/{galleryVideo}', [GalleryVideoController::class, 'update'])
        ->name('admin.gallery.videos.update');
    Route::post('/gallery/videos/{galleryVideo}/approve', [GalleryVideoController::class, 'approve'])
        ->name('admin.gallery.videos.approve');
    Route::delete('/gallery/videos/{galleryVideo}', [GalleryVideoController::class, 'destroy'])
        ->name('admin.gallery.videos.destroy');
});
