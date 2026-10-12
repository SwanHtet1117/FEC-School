<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post['title'] }} - FEC International School</title>
    <meta name="description" content="{{ $post['excerpt'] }}">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    @include('partials.header')

    <main class="post-detail-page">
        <div class="post-detail-container">
            <a href="{{ url('/news-events/all-posts') }}" class="post-back-link">
                <i class="fas fa-arrow-left" aria-hidden="true"></i> All posts
            </a>

            <article class="post-detail fade-in-up">
                <div class="post-detail-heading">
                    <span class="post-detail-category">{{ $post['category'] }}</span>
                    <h1>{{ $post['title'] }}</h1>
                    <div class="post-detail-meta">
                        <span><i class="fas fa-calendar" aria-hidden="true"></i> {{ $post['date'] }}</span>
                        @if (isset($post['location']))
                            <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $post['location'] }}</span>
                        @elseif (isset($post['department']))
                            <span><i class="fas fa-user" aria-hidden="true"></i> {{ $post['department'] }}</span>
                        @endif
                    </div>
                </div>

                <img class="post-detail-image" src="{{ asset('image/' . $post['image']) }}" alt="{{ $post['alt'] }}">

                <div class="post-detail-copy">
                    <p class="post-detail-lead">{{ $post['excerpt'] }}</p>
                    <p>{{ $post['body'] }}</p>
                </div>

                <a href="{{ url('/news-events/all-posts') }}" class="post-back-bottom">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Browse all posts
                </a>
            </article>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>