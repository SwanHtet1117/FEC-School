<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main.home');
});

Route::get('/about', function () {
    return view('main.about');
});

Route::get('/contact', function () {
    return view('main.contact');
});

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
    
