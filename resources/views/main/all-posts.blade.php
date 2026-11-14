<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category ? $category.' | ' : '' }}All Posts - FEC International School</title>
    <meta name="description" content="Browse all news, events, and announcements from FEC International School.">
    <meta name="keywords" content="FEC International School, News, Events, Announcements, All Posts">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    @include('partials.header')

    <section class="news-events-hero fade-in-up">
        <h1>{{ $category === 'Event' ? 'Events' : ($category ? $category : 'All Posts') }}</h1>
        <p>Browse news, events, and announcements from FEC International School.</p>
    </section>

    <main class="news-events-container">
        <nav class="news-tabs fade-in-up" aria-label="Post categories">
            <a class="news-tab {{ $category === null ? 'active' : '' }}" href="{{ route('news-events.all') }}">All Posts</a>
            <a class="news-tab {{ $category === 'News' ? 'active' : '' }}" href="{{ route('news-events.all', ['category' => 'News']) }}">News</a>
            <a class="news-tab {{ $category === 'Event' ? 'active' : '' }}" href="{{ route('news-events.all', ['category' => 'Event']) }}">Events</a>
            <a class="news-tab {{ $category === 'Announcement' ? 'active' : '' }}" href="{{ route('news-events.all', ['category' => 'Announcement']) }}">Announcements</a>
        </nav>

        <section class="news-section" aria-label="Posts">
            <div class="news-grid">
                @forelse ($posts as $post)
                    @include('main.partials.post-card', ['post' => $post])
                @empty
                    <p class="news-empty">There are no posts in this category yet.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
