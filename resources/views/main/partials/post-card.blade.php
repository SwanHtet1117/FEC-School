<article class="news-card fade-in-up">
    <img src="{{ $post->image_url }}" alt="{{ $post->alt_text }}" class="news-card-image">
    <span class="news-card-badge">{{ $post->category }}</span>
    <div class="news-card-content">
        <div class="news-card-date">
            <i class="fas fa-calendar" aria-hidden="true"></i> {{ $post->published_at->format('F j, Y') }}
        </div>
        <h3 class="news-card-title">{{ $post->title }}</h3>
        <p class="news-card-excerpt">{{ $post->excerpt }}</p>
        @if ($post->location)
            <div class="news-card-location">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $post->location }}
            </div>
        @elseif ($post->department)
            <div class="news-card-location">
                <i class="fas fa-user" aria-hidden="true"></i> {{ $post->department }}
            </div>
        @endif
        <a href="{{ route('news-events.posts.show', $post->slug) }}" class="news-card-link">
            {{ $post->category === 'Event' ? 'Event Details' : 'Read More' }}
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</article>
