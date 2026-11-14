<?php

use App\Http\Controllers\Admin\ContactMessageManagementController;
use App\Http\Controllers\Admin\CareerOpportunityController;
use App\Http\Controllers\Admin\EventCalendarController;
use App\Http\Controllers\Admin\FaqManagementController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\PostManagementController;
use App\Http\Controllers\Admin\SystemActivityController;
use App\Http\Controllers\Admin\TestimonialManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\PostController;
use App\Models\ContactMessage;
use App\Models\CareerOpportunity;
use App\Models\Faq;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\SystemActivity;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main.home', [
        'heroSlides' => HeroSlide::where('is_enabled', true)->ordered()->get(),
        'testimonials' => Testimonial::query()->orderBy('sort_order')->orderBy('id')->get(),
        'latestPosts' => Post::query()
            ->whereIn('category', ['News', 'Event'])
            ->latest('published_at')
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
    return view('main.campus-life');
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
    return view('main.photo-gallery');
});

Route::get('/gallery/video', function () {
    return view('main.video-gallery');
});

Route::get('/admissions', function () {
    return view('main.admission-overview', [
        'faqs' => Faq::query()->orderBy('sort_order')->orderBy('id')->get(),
    ]);
});

Route::get('/news-events/all-posts', [PostController::class, 'all'])->name('news-events.all');

Route::get('/news-events/posts/{slug}', [PostController::class, 'show'])->name('news-events.posts.show');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('register.store');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'heroImageCount' => HeroSlide::count(),
            'activities' => SystemActivity::latest()->limit(10)->get(),
            'messageCount' => ContactMessage::count(),
            'careerCount' => CareerOpportunity::open()->count(),
            'postCount' => Post::query()->count(),
            'faqCount' => Faq::count(),
            'testimonialCount' => Testimonial::count(),
            'userCount' => User::count(),
            'pendingCount' => User::where('is_approved', false)->count(),
            'adminCount' => User::where('is_admin', true)->count(),
        ]);
    })->name('admin.dashboard');

    Route::get('/activities', [SystemActivityController::class, 'index'])
        ->name('admin.activities.index');

    Route::get('/events/calendar', [EventCalendarController::class, 'index'])
        ->name('admin.events.calendar');

    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/users/{user}/approve', [UserManagementController::class, 'approve'])
        ->name('admin.users.approve');
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
});
