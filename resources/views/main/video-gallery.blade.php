<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Gallery - FEC International School</title>
    <meta name="description" content="Browse through our video gallery showcasing school events, activities, and campus life at FEC International School.">
    <meta name="keywords" content="FEC International School, Video Gallery, School Events, Campus Life">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
    <style>
        .video-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .video-modal.active {
            display: flex;
        }
        .video-modal-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }
        .video-modal-content iframe {
            width: min(90vw, 420px, calc(80vh * 9 / 16));
            height: min(80vh, 746px);
            aspect-ratio: 9 / 16;
            border-radius: var(--radius-md);
            border: 0;
            background: #000000;
        }
        .video-placeholder.has-video {
            border: 0;
            background: #0f132a;
        }
        .video-thumbnail {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .video-placeholder.has-video > .fa-play-circle,
        .video-placeholder.has-video > span:first-of-type {
            display: none;
        }
        .video-placeholder.has-video .video-item-info,
        .video-placeholder.has-video .video-play-btn {
            z-index: 1;
        }
        .video-modal-close {
            position: absolute;
            top: -40px;
            right: 0;
            color: #FFFFFF;
            font-size: 32px;
            cursor: pointer;
            transition: var(--transition);
        }
        .video-modal-close:hover {
            color: var(--accent-gold);
        }
    </style>
</head>
<body class="video-gallery-page">
    
    @include('partials.header')

        <section class="news-events-hero video-gallery-hero fade-in-up">
            <h1 class="page-title">Video Gallery</h1>
            <p class="page-subtitle">Watch Our Stories Unfold</p>
        </section>

        <section class="content-section">
            <div class="section-container">
                <div class="gallery-intro fade-in-up">
                    <p>Welcome to our video gallery, where we bring the vibrant life and memorable moments at FEC International School to motion. From academic achievements to cultural celebrations, sports events to community activities, watch the rich experiences that define our school community come alive.</p>
                </div>

                <div class="video-gallery-toolbar" role="group" aria-label="Video gallery filters">
                    <label class="video-gallery-field video-gallery-search">
                        <span>Search videos</span>
                        <input id="videoGallerySearch" type="search" placeholder="Search by title or category">
                    </label>
                    <label class="video-gallery-field">
                        <span>Category</span>
                        <select id="videoGalleryCategory">
                            <option value="all">All categories</option>
                            <option value="academics">Academics</option>
                            <option value="campus-life">Campus Life</option>
                            <option value="events">School Events</option>
                        </select>
                    </label>
                    <label class="video-gallery-field">
                        <span>Sort by</span>
                        <select id="videoGallerySort">
                            <option value="featured">Featured</option>
                            <option value="title">Title (A-Z)</option>
                            <option value="category">Category</option>
                            <option value="views">Most viewed</option>
                            <option value="duration">Duration</option>
                        </select>
                    </label>
                    <div class="video-gallery-field">
                        <span>View mode</span>
                        <div class="video-gallery-view-mode" role="group" aria-label="View mode">
                            <button type="button" class="active" data-view-mode="grid" aria-pressed="true">
                                <i class="fa-solid fa-grip" aria-hidden="true"></i> Grid
                            </button>
                            <button type="button" data-view-mode="list" aria-pressed="false">
                                <i class="fa-solid fa-list" aria-hidden="true"></i> List
                            </button>
                        </div>
                    </div>
                    <button class="video-gallery-reset" id="videoGalleryReset" type="button">
                        <i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i> Reset
                    </button>
                </div>

                <div class="video-gallery-result-count">
                    <p id="videoGalleryCount" aria-live="polite">{{ $videos->count() }} videos</p>
                </div>
                
                <p class="video-gallery-empty" id="videoGalleryEmpty" hidden>
                    No videos match your filters. Try changing your search or category.
                </p>
            </div>
        </section>  
    

    @foreach ($videos->groupBy('category') as $category => $categoryVideos)
        @php($categoryLabel = $categoryVideos->first()->category_label)
        <section class="content-section {{ $loop->first ? 'light-bg' : '' }} video-gallery-category-section"
            data-category="{{ $category }}" data-category-label="{{ $categoryLabel }}">
            <div class="section-container">
                <h2 class="section-title fade-in-up">{{ $categoryLabel }}</h2>
                <div class="gallery-grid video-gallery-grid fade-in-up">
                    @foreach ($categoryVideos as $video)
                        <div class="gallery-item">
                            <div class="video-placeholder {{ $video->youtube_id ? 'has-video' : 'video-unavailable' }}"
                                @if ($video->youtube_id)
                                    onclick="openVideoModal(this)" role="button" tabindex="0"
                                    aria-label="Play {{ $video->title }}" data-video-id="{{ $video->youtube_id }}"
                                @endif
                                data-title="{{ $video->title }}"
                                data-views="{{ number_format($video->views) }}"
                                data-duration="{{ $video->duration }}">
                                @if ($video->thumbnail_url)
                                    <img class="video-thumbnail" src="{{ $video->thumbnail_url }}" alt="{{ $video->title }} video thumbnail">
                                @else
                                    <i class="fas fa-play-circle" aria-hidden="true"></i>
                                    <span>{{ $categoryLabel }}</span>
                                @endif
                                <div class="video-item-info">
                                    <span class="video-item-title">{{ $video->title }}</span>
                                    <div class="video-item-meta">
                                        <span class="video-item-views"><i class="fas fa-eye" aria-hidden="true"></i> {{ number_format($video->views) }}</span>
                                        <span class="video-item-duration">
                                            <i class="fas {{ $video->youtube_id ? 'fa-video' : 'fa-clock' }}" aria-hidden="true"></i>
                                            {{ $video->duration }}
                                        </span>
                                    </div>
                                </div>
                                @if ($video->youtube_id)
                                    <div class="video-play-btn"><i class="fas fa-play" aria-hidden="true"></i></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    @include('partials.footer')

    <!-- Video Modal -->
    <div class="video-modal" id="videoModal" onclick="closeVideoModal(event)">
        <div class="video-modal-content">
            <span class="video-modal-close" onclick="closeVideoModal(event)">&times;</span>
            <iframe id="videoModalFrame" title="FEC School Student Activity" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </div>

    <script>
        function openVideoModal(element) {
            const videoId = element.getAttribute('data-video-id');
            if (!videoId) {
                return;
            }

            const modal = document.getElementById('videoModal');
            const frame = document.getElementById('videoModalFrame');
            frame.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&playsinline=1&rel=0`;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal(event) {
            if (event.target.classList.contains('video-modal') || event.target.classList.contains('video-modal-close')) {
                const modal = document.getElementById('videoModal');
                modal.classList.remove('active');
                document.getElementById('videoModalFrame').src = '';
                document.body.style.overflow = 'auto';
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('videoModal');
                modal.classList.remove('active');
                document.getElementById('videoModalFrame').src = '';
                document.body.style.overflow = 'auto';
            }
        });

        const videoGallerySections = Array.from(document.querySelectorAll('.video-gallery-category-section'));
        const videoGalleryCards = Array.from(document.querySelectorAll('.video-gallery-category-section .gallery-item'));
        const videoGallerySearch = document.getElementById('videoGallerySearch');
        const videoGalleryCategory = document.getElementById('videoGalleryCategory');
        const videoGallerySort = document.getElementById('videoGallerySort');
        const videoGalleryCount = document.getElementById('videoGalleryCount');
        const videoGalleryEmpty = document.getElementById('videoGalleryEmpty');
        const videoGalleryModeButtons = Array.from(document.querySelectorAll('[data-view-mode]'));

        videoGalleryCards.forEach((card, index) => {
            const video = card.querySelector('.video-placeholder');
            const section = card.closest('.video-gallery-category-section');
            const title = video.dataset.title;

            card.dataset.category = section.dataset.category;
            card.dataset.title = title;
            card.dataset.views = String(Number(video.dataset.views.replaceAll(',', '')));
            card.dataset.duration = video.dataset.duration;
            card.dataset.order = String(index);
            card.dataset.search = `${title} ${section.dataset.categoryLabel}`.toLowerCase();

            if (video.dataset.videoId) {
                video.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openVideoModal(video);
                    }
                });
            }
        });

        function durationInSeconds(duration) {
            const parts = duration.split(':').map(Number);

            if (parts.length === 2) {
                return parts[0] * 60 + parts[1];
            }

            if (parts.length === 3) {
                return parts[0] * 3600 + parts[1] * 60 + parts[2];
            }

            return 0;
        }

        function compareVideoCards(first, second) {
            switch (videoGallerySort.value) {
                case 'title':
                    return first.dataset.title.localeCompare(second.dataset.title);
                case 'category':
                    return first.dataset.category.localeCompare(second.dataset.category)
                        || first.dataset.title.localeCompare(second.dataset.title);
                case 'views':
                    return Number(second.dataset.views) - Number(first.dataset.views);
                case 'duration':
                    return durationInSeconds(first.dataset.duration) - durationInSeconds(second.dataset.duration);
                default:
                    return Number(first.dataset.order) - Number(second.dataset.order);
            }
        }

        function updateVideoGallery() {
            const searchTerm = videoGallerySearch.value.trim().toLowerCase();
            const selectedCategory = videoGalleryCategory.value;
            const matchingCards = videoGalleryCards.filter((card) => {
                const matchesCategory = selectedCategory === 'all' || card.dataset.category === selectedCategory;
                const matchesSearch = card.dataset.search.includes(searchTerm);

                return matchesCategory && matchesSearch;
            });

            videoGallerySections.forEach((section) => {
                const sectionCards = matchingCards
                    .filter((card) => card.closest('.video-gallery-category-section') === section)
                    .sort(compareVideoCards);
                const grid = section.querySelector('.video-gallery-grid');

                sectionCards.forEach((card) => grid.append(card));
                section.hidden = sectionCards.length === 0;
            });

            const videoWord = matchingCards.length === 1 ? 'video' : 'videos';
            videoGalleryCount.textContent = `${matchingCards.length} ${videoWord}`;
            videoGalleryEmpty.hidden = matchingCards.length > 0;
        }

        function setVideoGalleryMode(mode) {
            document.querySelectorAll('.video-gallery-grid').forEach((grid) => {
                grid.classList.toggle('is-list', mode === 'list');
            });

            videoGalleryModeButtons.forEach((button) => {
                const isActive = button.dataset.viewMode === mode;
                button.classList.toggle('active', isActive);
                button.setAttribute('aria-pressed', String(isActive));
            });
        }

        function resetVideoGallery() {
            videoGallerySearch.value = '';
            videoGalleryCategory.value = 'all';
            videoGallerySort.value = 'featured';
            setVideoGalleryMode('grid');
            updateVideoGallery();
        }

        videoGallerySearch.addEventListener('input', updateVideoGallery);
        videoGalleryCategory.addEventListener('change', updateVideoGallery);
        videoGallerySort.addEventListener('change', updateVideoGallery);
        document.getElementById('videoGalleryReset').addEventListener('click', resetVideoGallery);
        videoGalleryModeButtons.forEach((button) => {
            button.addEventListener('click', () => setVideoGalleryMode(button.dataset.viewMode));
        });

        updateVideoGallery();
    </script>
</body>
</html>
