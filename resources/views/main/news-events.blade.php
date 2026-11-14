<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News &amp; Events - FEC International School</title>
    <meta name="description" content="Stay updated with FEC International School's latest news, events, and announcements.">
    <meta name="keywords" content="FEC International School, News, Events, Announcements">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/news-events-calendar.css') }}">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    @include('partials.header')

    <section class="news-events-hero fade-in-up">
        <h1>News &amp; Events</h1>
        <p>Stay updated with the latest news, announcements, and activities from our school community.</p>
    </section>

    <main class="news-events-container">
        @if ($announcements->isNotEmpty())
            <section class="announcement-banner fade-in-up" aria-label="Latest announcement">
                <h3><i class="fas fa-bullhorn" aria-hidden="true"></i> Latest Announcement</h3>
                <p>{{ $announcements->first()->title }} — {{ $announcements->first()->excerpt }}</p>
                <a href="{{ route('news-events.posts.show', $announcements->first()->slug) }}">Read announcement</a>
            </section>
        @endif

        <section class="news-section">
            <h2 class="news-section-title fade-in-up">Latest News</h2>
            <div class="news-grid">
                @forelse ($news as $post)
                    @include('main.partials.post-card', ['post' => $post])
                @empty
                    <p class="news-empty">There are no news posts yet.</p>
                @endforelse
            </div>
            <div class="view-all-link fade-in-up">
                <a href="{{ route('news-events.all', ['category' => 'News']) }}" class="btn-view-all">
                    View All News <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>

        <section class="news-section">
            <h2 class="news-section-title fade-in-up">Events</h2>
            <div class="news-grid">
                @forelse ($events as $post)
                    @include('main.partials.post-card', ['post' => $post])
                @empty
                    <p class="news-empty">There are no events listed yet.</p>
                @endforelse
            </div>
            <div class="view-all-link fade-in-up">
                <a href="{{ route('news-events.all', ['category' => 'Event']) }}" class="btn-view-all">
                    View All Events <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>

        <section class="news-section public-event-calendar-section" aria-labelledby="public-calendar-title">
            <div class="public-event-calendar-heading">
                <div>
                    <h2 class="news-section-title" id="public-calendar-title">Event Calendar</h2>
                    <p>Browse school events by date.</p>
                </div>
                <span class="public-event-calendar-count">
                    {{ $calendarEventCount }} {{ $calendarEventCount === 1 ? 'event' : 'events' }} this month
                </span>
            </div>

            <div class="public-event-calendar">
                <div class="public-event-calendar-toolbar">
                    <div class="public-event-calendar-navigation">
                        <a class="public-event-calendar-nav-button"
                            href="{{ route('news-events.index', ['month' => $calendarMonth->copy()->subMonth()->format('Y-m')]) }}"
                            aria-label="Previous month">
                            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <h3 id="public-calendar-month">{{ $calendarMonth->format('F Y') }}</h3>
                        <a class="public-event-calendar-nav-button"
                            href="{{ route('news-events.index', ['month' => $calendarMonth->copy()->addMonth()->format('Y-m')]) }}"
                            aria-label="Next month">
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </div>
                    <a class="public-event-calendar-today" href="{{ route('news-events.index', ['month' => today()->format('Y-m')]) }}">This month</a>
                </div>

                <div class="public-event-calendar-scroll">
                    <div class="public-event-calendar-grid" role="grid" aria-labelledby="public-calendar-month">
                        @foreach ($calendarWeekdays as $weekday)
                            <div class="public-event-calendar-weekday" role="columnheader">{{ $weekday }}</div>
                        @endforeach

                        @foreach ($calendarDays as $day)
                            <div class="public-event-calendar-day {{ $day['isCurrentMonth'] ? '' : 'outside-month' }} {{ $day['date']->isToday() ? 'today' : '' }}" role="gridcell">
                                <span class="public-event-calendar-day-number">{{ $day['date']->day }}</span>
                                <div class="public-event-calendar-day-events">
                                    @foreach ($day['events'] as $event)
                                        <a class="public-event-calendar-event" href="{{ route('news-events.posts.show', $event->slug) }}">
                                            <span>{{ $event->title }}</span>
                                            @if ($event->location)
                                                <small>{{ $event->location }}</small>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($calendarEventCount === 0)
                    <p class="public-event-calendar-empty">No events are scheduled for {{ $calendarMonth->format('F Y') }}.</p>
                @endif
            </div>
        </section>

        <section class="news-section">
            <h2 class="news-section-title fade-in-up">Announcements</h2>
            <div class="news-grid">
                @forelse ($announcements as $post)
                    @include('main.partials.post-card', ['post' => $post])
                @empty
                    <p class="news-empty">There are no announcements yet.</p>
                @endforelse
            </div>
            <div class="view-all-link fade-in-up">
                <a href="{{ route('news-events.all', ['category' => 'Announcement']) }}" class="btn-view-all">
                    View All Announcements <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
