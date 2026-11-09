<?php

use App\Http\Controllers\Admin\ContactMessageManagementController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\PostController;
use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\SystemActivity;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main.home', [
        'heroSlides' => HeroSlide::where('is_enabled', true)->ordered()->get(),
    ]);
})->name('home');

Route::get('/about', function () {
    return view('main.about');
});

Route::get('/contact', function () {
    return view('main.contact');
})->name('contact');

Route::post('/contact', [ContactMessageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.messages.store');

Route::get('/news-events', function () {
    return view('main.news-events');
});

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
    return view('main.admission-overview');
});

Route::get('/news-events/all-posts', function () {
    return view('main.all-posts');
});

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
            'userCount' => User::count(),
            'pendingCount' => User::where('is_approved', false)->count(),
            'adminCount' => User::where('is_admin', true)->count(),
        ]);
    })->name('admin.dashboard');

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

    Route::get('/hero/manage', [HeroSlideController::class, 'index'])->name('admin.hero.manage');
    Route::post('/hero/manage', [HeroSlideController::class, 'store'])->name('admin.hero.store');
    Route::put('/hero/manage/{heroSlide}', [HeroSlideController::class, 'update'])
        ->name('admin.hero.update');
    Route::delete('/hero/manage/{heroSlide}', [HeroSlideController::class, 'destroy'])
        ->name('admin.hero.destroy');
    Route::post('/hero/manage/{heroSlide}/toggle', [HeroSlideController::class, 'toggle'])
        ->name('admin.hero.toggle');
});
