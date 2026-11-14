<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\EventCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PostController extends Controller
{
    public function newsEvents(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'month' => ['nullable', 'date_format:Y-m'],
        ]);
        $monthValue = $validator->passes() && filled($validator->validated()['month'] ?? null)
            ? $validator->validated()['month']
            : now()->format('Y-m');
        $calendarMonth = Carbon::parse($monthValue.'-01')->startOfMonth();
        $calendarEvents = Post::query()
            ->where('category', 'Event')
            ->whereBetween('published_at', [
                $calendarMonth->toDateString(),
                $calendarMonth->copy()->endOfMonth()->toDateString(),
            ])
            ->orderBy('published_at')
            ->orderBy('title')
            ->get();

        return view('main.news-events', [
            'news' => Post::query()->where('category', 'News')->latest('published_at')->limit(3)->get(),
            'announcements' => Post::query()->where('category', 'Announcement')->latest('published_at')->limit(3)->get(),
            'events' => Post::query()->where('category', 'Event')->latest('published_at')->limit(3)->get(),
            'calendarMonth' => $calendarMonth,
            'calendarDays' => EventCalendar::days($calendarMonth, $calendarEvents),
            'calendarEventCount' => $calendarEvents->count(),
            'calendarWeekdays' => EventCalendar::WEEKDAYS,
        ]);
    }

    public function all(Request $request): View
    {
        $category = $request->query('category');
        abort_if($category !== null && ! in_array($category, ['News', 'Announcement', 'Event'], true), 404);

        $posts = Post::query()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest('published_at')
            ->get();

        return view('main.all-posts', [
            'posts' => $posts,
            'category' => $category,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->firstOrFail();

        return view('main.post-detail', ['post' => $post]);
    }
}
