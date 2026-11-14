<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\EventCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class EventCalendarController extends Controller
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'month' => ['nullable', 'date_format:Y-m'],
        ]);
        $monthValue = $validator->passes() && filled($validator->validated()['month'] ?? null)
            ? $validator->validated()['month']
            : now()->format('Y-m');
        $month = Carbon::parse($monthValue.'-01')->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();
        $events = Post::query()
            ->where('category', 'Event')
            ->whereBetween('published_at', [$month->toDateString(), $monthEnd->toDateString()])
            ->orderBy('published_at')
            ->orderBy('title')
            ->get();

        return view('admin.event-calendar', [
            'month' => $month,
            'previousMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
            'calendarDays' => EventCalendar::days($month, $events),
            'weekdays' => EventCalendar::WEEKDAYS,
            'eventCount' => $events->count(),
            'filterErrors' => $validator->errors(),
        ]);
    }
}
