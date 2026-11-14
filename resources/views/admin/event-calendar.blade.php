<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage FEC International School events on the calendar">
    <title>Event Calendar | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-event-calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-delete-confirmation.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container event-calendar-management">
        <header class="event-calendar-heading">
            <div class="event-calendar-heading-copy">
                <a class="event-calendar-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <p class="event-calendar-eyebrow">SCHOOL EVENTS</p>
                <h1>Event Calendar</h1>
                <p>Plan, review, and manage important dates for your school community.</p>
            </div>
            <div class="event-calendar-heading-actions">
                <a class="calendar-button secondary" href="{{ route('admin.posts.index', ['category' => 'Event']) }}">
                    <i class="fa-solid fa-list" aria-hidden="true"></i> Event posts
                </a>
                <a class="calendar-button primary" href="{{ route('admin.posts.index', ['category' => 'Event']) }}#post-create-title">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Add event
                </a>
            </div>
        </header>

        @if ($filterErrors->any())
            <div class="event-calendar-notice" role="alert">
                Invalid month selection. Showing the current month instead.
            </div>
        @endif

        <section class="event-calendar-summary" aria-label="Calendar summary">
            <div class="event-calendar-summary-count">
                <span class="event-calendar-summary-icon" aria-hidden="true">
                    <i class="fa-regular fa-calendar-check"></i>
                </span>
                <div>
                    <span class="event-calendar-summary-label">EVENTS THIS MONTH</span>
                    <strong>{{ $eventCount }}</strong>
                </div>
            </div>
            <div class="event-calendar-summary-copy">
                <span>{{ $month->format('F Y') }}</span>
                <p>Events added here also appear on your public News &amp; Events page.</p>
            </div>
        </section>

        <section class="event-calendar-panel" aria-labelledby="calendar-month">
            <div class="event-calendar-toolbar">
                <div class="event-month-navigation">
                    <a class="calendar-icon-button" href="{{ route('admin.events.calendar', ['month' => $previousMonth]) }}" aria-label="Previous month">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </a>
                    <h2 id="calendar-month">{{ $month->format('F Y') }}</h2>
                    <a class="calendar-icon-button" href="{{ route('admin.events.calendar', ['month' => $nextMonth]) }}" aria-label="Next month">
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    </a>
                </div>
                <a class="calendar-button secondary" href="{{ route('admin.events.calendar', ['month' => today()->format('Y-m')]) }}">Today</a>
            </div>

            <div class="event-calendar-scroll">
                <div class="event-calendar-grid" role="grid" aria-labelledby="calendar-month">
                    @foreach ($weekdays as $weekday)
                        <div class="event-calendar-weekday" role="columnheader">{{ $weekday }}</div>
                    @endforeach

                    @foreach ($calendarDays as $day)
                        <div class="event-calendar-day {{ $day['isCurrentMonth'] ? '' : 'outside-month' }} {{ $day['date']->isToday() ? 'today' : '' }}" role="gridcell">
                            <span class="event-calendar-day-number">{{ $day['date']->day }}</span>
                            <div class="event-calendar-day-items">
                                @foreach ($day['events'] as $event)
                                    <article class="event-calendar-item">
                                        <a class="event-calendar-item-title" href="{{ route('admin.posts.edit', $event) }}">
                                            {{ $event->title }}
                                        </a>
                                        @if ($event->location)
                                            <span class="event-calendar-item-location">{{ $event->location }}</span>
                                        @endif
                                        <div class="event-calendar-item-actions">
                                            <a href="{{ route('admin.posts.edit', $event) }}" aria-label="Edit {{ $event->title }}">
                                                <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                                            </a>
                                            <form data-delete-confirmation data-delete-type="event" data-delete-name="{{ $event->title }}" action="{{ route('admin.posts.destroy', $event) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" aria-label="Delete {{ $event->title }}">
                                                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($eventCount === 0)
                <div class="event-calendar-empty">
                    <span class="event-calendar-empty-icon" aria-hidden="true">
                        <i class="fa-regular fa-calendar-plus"></i>
                    </span>
                    <div>
                        <strong>No events this month</strong>
                        <p>There are no events scheduled for {{ $month->format('F Y') }} yet.</p>
                    </div>
                    <a class="calendar-button primary" href="{{ route('admin.posts.index', ['category' => 'Event']) }}#post-create-title">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i> Add an event
                    </a>
                </div>
            @endif
        </section>

        @include('admin.partials.delete-confirmation')
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/admin-delete-confirmation.js') }}" defer></script>
</body>
</html>
