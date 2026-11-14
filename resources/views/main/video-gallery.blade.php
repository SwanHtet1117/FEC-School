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
                    <p id="videoGalleryCount" aria-live="polite">12 videos</p>
                </div>
                
                <p class="video-gallery-empty" id="videoGalleryEmpty" hidden>
                    No videos match your filters. Try changing your search or category.
                </p>
            </div>
        </section>  
    

    <section class="content-section light-bg video-gallery-category-section" data-category="events" data-category-label="School Events">
        <div class="section-container">
            <h2 class="section-title fade-in-up">School Events</h2>
            <div class="gallery-grid video-gallery-grid fade-in-up">
                <div class="gallery-item">
                    <div class="video-placeholder has-video" onclick="openVideoModal(this)" data-video-id="s9xq0rOPltg" data-title="FEC School Student Activity" data-views="5,678" data-duration="Short">
                        <img class="video-thumbnail" src="https://i.ytimg.com/vi/s9xq0rOPltg/hqdefault.jpg" alt="FEC School Student Activity video thumbnail">
                        <div class="video-item-info">
                            <span class="video-item-title">FEC School Student Activity</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 5,678</span>
                                <span class="video-item-duration"><i class="fas fa-video"></i> Short</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Science Fair Exhibition" data-views="4,321" data-duration="8:45">
                        <i class="fas fa-play-circle"></i>
                        <span>Event Video 2</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Science Fair Exhibition</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 4,321</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 8:45</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Cultural Festival" data-views="7,892" data-duration="15:20">
                        <i class="fas fa-play-circle"></i>
                        <span>Event Video 3</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Cultural Festival</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 7,892</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 15:20</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Graduation Ceremony" data-views="9,456" data-duration="25:10">
                        <i class="fas fa-play-circle"></i>
                        <span>Event Video 4</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Graduation Ceremony</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 9,456</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 25:10</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section video-gallery-category-section" data-category="campus-life" data-category-label="Campus Life">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Campus Life</h2>
            <div class="gallery-grid video-gallery-grid fade-in-up">
                <div class="gallery-item">
                    <div class="video-placeholder has-video" onclick="openVideoModal(this)" data-video-id="s9xq0rOPltg" data-title="FEC School Student Activity" data-views="3,456" data-duration="Short">
                        <img class="video-thumbnail" src="https://i.ytimg.com/vi/s9xq0rOPltg/hqdefault.jpg" alt="FEC School Student Activity video thumbnail">
                        <div class="video-item-info">
                            <span class="video-item-title">FEC School Student Activity</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 3,456</span>
                                <span class="video-item-duration"><i class="fas fa-video"></i> Short</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Student Life" data-views="2,789" data-duration="7:30">
                        <i class="fas fa-play-circle"></i>
                        <span>Campus Video 2</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Student Life</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 2,789</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 7:30</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Facilities Overview" data-views="4,123" data-duration="9:45">
                        <i class="fas fa-play-circle"></i>
                        <span>Campus Video 3</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Facilities Overview</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 4,123</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 9:45</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Extracurricular Activities" data-views="3,890" data-duration="11:20">
                        <i class="fas fa-play-circle"></i>
                        <span>Campus Video 4</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Extracurricular Activities</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 3,890</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 11:20</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section light-bg video-gallery-category-section" data-category="academics" data-category-label="Academics">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Academic Activities</h2>
            <div class="gallery-grid video-gallery-grid fade-in-up">
                <div class="gallery-item">
                    <div class="video-placeholder has-video" onclick="openVideoModal(this)" data-video-id="s9xq0rOPltg" data-title="FEC School Student Activity" data-views="6,234" data-duration="Short">
                        <img class="video-thumbnail" src="https://i.ytimg.com/vi/s9xq0rOPltg/hqdefault.jpg" alt="FEC School Student Activity video thumbnail">
                        <div class="video-item-info">
                            <span class="video-item-title">FEC School Student Activity</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 6,234</span>
                                <span class="video-item-duration"><i class="fas fa-video"></i> Short</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Science Experiments" data-views="5,123" data-duration="12:10">
                        <i class="fas fa-play-circle"></i>
                        <span>Academic Video 2</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Science Experiments</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 5,123</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 12:10</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Art & Music Classes" data-views="4,567" data-duration="10:50">
                        <i class="fas fa-play-circle"></i>
                        <span>Academic Video 3</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Art & Music Classes</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 4,567</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 10:50</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Sports Training" data-views="5,890" data-duration="13:40">
                        <i class="fas fa-play-circle"></i>
                        <span>Academic Video 4</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Sports Training</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 5,890</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 13:40</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            const modal = document.getElementById('videoModal');
            const videoId = element.getAttribute('data-video-id');
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
