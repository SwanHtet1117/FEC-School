<?php

namespace App\Support;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EventCalendar
{
    public const WEEKDAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public static function days(Carbon $month, Collection $events): Collection
    {
        $eventsByDate = $events->groupBy(
            static fn (Post $event): string => $event->published_at->toDateString()
        );
        $monthEnd = $month->copy()->endOfMonth();
        $calendarStart = $month->copy()->startOfWeek(Carbon::SUNDAY);
        $calendarEnd = $monthEnd->copy()->endOfWeek(Carbon::SATURDAY);
        $calendarDays = collect();

        for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
            $day = $date->copy();
            $dateKey = $day->toDateString();
            $calendarDays->push([
                'date' => $day,
                'isCurrentMonth' => $day->format('Y-m') === $month->format('Y-m'),
                'events' => $eventsByDate->get($dateKey, collect()),
            ]);
        }

        return $calendarDays;
    }
}
