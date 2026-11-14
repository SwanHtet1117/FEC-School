<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery - FEC International School</title>
    <meta name="description" content="Browse through our photo gallery showcasing school events, activities, and campus life at FEC International School.">
    <meta name="keywords" content="FEC International School, Photo Gallery, School Events, Campus Life">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body class="photo-gallery-page">
    @php
        $galleryPhotos = [
            [
                'title' => 'School Gathering',
                'category' => 'events',
                'categoryLabel' => 'School Events',
                'image' => 'all_student_activity_01.jpg',
                'alt' => 'Students gathered outdoors at school',
                'views' => 1234,
            ],
            [
                'title' => 'Student Project Showcase',
                'category' => 'events',
                'categoryLabel' => 'School Events',
                'image' => 'early_student_activity_01.jpg',
                'alt' => 'Early Years students presenting a classroom project',
                'views' => 987,
            ],
            [
                'title' => 'Student Activity',
                'category' => 'events',
                'categoryLabel' => 'School Events',
                'image' => 'all_student_activity_02.jpg',
                'alt' => 'Students taking part in a classroom activity',
                'views' => 2156,
            ],
            [
                'title' => 'School Assembly',
                'category' => 'events',
                'categoryLabel' => 'School Events',
                'image' => 'secondary_student_activity_02.jpg',
                'alt' => 'Secondary students gathered for a school event',
                'views' => 3421,
            ],
            [
                'title' => 'FEC School Building',
                'category' => 'campus-life',
                'categoryLabel' => 'Campus Life',
                'image' => 'fec_building_01.jpg',
                'alt' => 'Exterior view of the FEC school building',
                'views' => 856,
            ],
            [
                'title' => 'Campus Grounds',
                'category' => 'campus-life',
                'categoryLabel' => 'Campus Life',
                'image' => 'fec_building_02.jpg',
                'alt' => 'FEC campus grounds and school building',
                'views' => 743,
            ],
            [
                'title' => 'School Campus',
                'category' => 'campus-life',
                'categoryLabel' => 'Campus Life',
                'image' => 'fec_building.jpg',
                'alt' => 'FEC International School campus',
                'views' => 1102,
            ],
            [
                'title' => 'FEC Campus Overview',
                'category' => 'campus-life',
                'categoryLabel' => 'Campus Life',
                'image' => 'fec_wallpaper.jpg',
                'alt' => 'Illustrated overview of the FEC campus and school community',
                'views' => 634,
            ],
            [
                'title' => 'Classroom Learning',
                'category' => 'academics',
                'categoryLabel' => 'Academic Activities',
                'image' => 'secondary_student_activity_01.jpg',
                'alt' => 'Secondary students working in their classroom',
                'views' => 1567,
            ],
            [
                'title' => 'Group Study Session',
                'category' => 'academics',
                'categoryLabel' => 'Academic Activities',
                'image' => 'primary_student_activity_01.jpg',
                'alt' => 'Primary students studying together in class',
                'views' => 923,
            ],
            [
                'title' => 'Early Years Classroom',
                'category' => 'academics',
                'categoryLabel' => 'Academic Activities',
                'image' => 'early_student_activity_04.jpg',
                'alt' => 'Early Years students learning with their teacher',
                'views' => 1089,
            ],
            [
                'title' => 'GED Student Activity',
                'category' => 'academics',
                'categoryLabel' => 'Academic Activities',
                'image' => 'ged_student_activity_01.jpg',
                'alt' => 'GED students participating in a school activity',
                'views' => 756,
            ],
        ];
    @endphp

    @include('partials.header')

    <section class="news-events-hero photo-gallery-hero fade-in-up">
        <h1 class="page-title">Photo Gallery</h1>
        <p class="page-subtitle">Capturing Moments at FEC International School</p>
    </section>

    <main class="photo-gallery-page-content">
        <section class="content-section">
            <div class="section-container">
                <div class="gallery-intro">
                    <p>Welcome to our photo gallery, where we showcase the vibrant life and memorable moments at FEC International School. From academic achievements to cultural celebrations, sports events to community activities, explore the rich experiences that define our school community.</p>
                </div>

                <div class="photo-gallery-toolbar" aria-label="Photo gallery filters">
                    <label class="photo-gallery-field photo-gallery-search">
                        <span>Search photos</span>
                        <span class="photo-gallery-search-input">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            <input id="photoGallerySearch" type="search" placeholder="Search by title or description">
                        </span>
                    </label>
                    <label class="photo-gallery-field">
                        <span>Category</span>
                        <select id="photoGalleryCategory">
                            <option value="all">All categories</option>
                            <option value="academics">Academic Activities</option>
                            <option value="campus-life">Campus Life</option>
                            <option value="events">School Events</option>
                        </select>
                    </label>
                    <label class="photo-gallery-field">
                        <span>Sort by</span>
                        <select id="photoGallerySort">
                            <option value="featured">Featured</option>
                            <option value="title">Title (A-Z)</option>
                            <option value="views">Most viewed</option>
                        </select>
                    </label>
                    <button class="photo-gallery-reset" id="photoGalleryReset" type="button">
                        <i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i> Reset
                    </button>
                </div>

                <div class="photo-gallery-result-count">
                    <p id="photoGalleryCount" aria-live="polite">{{ count($galleryPhotos) }} photos</p>
                </div>

                <div class="photo-gallery-grid" id="photoGalleryGrid">
                    @foreach ($galleryPhotos as $photo)
                        <article class="photo-gallery-card"
                            data-category="{{ $photo['category'] }}"
                            data-title="{{ $photo['title'] }}"
                            data-search="{{ $photo['title'] }} {{ $photo['categoryLabel'] }} {{ $photo['alt'] }}"
                            data-views="{{ $photo['views'] }}">
                            <button class="photo-gallery-image-button" type="button"
                                aria-label="View {{ $photo['title'] }}"
                                onclick="openModal(this.closest('.photo-gallery-card'))">
                                <img class="gallery-photo" src="{{ asset('image/'.$photo['image']) }}" alt="{{ $photo['alt'] }}" loading="lazy">
                            </button>
                            <div class="photo-gallery-card-content">
                                <span class="photo-gallery-category">{{ $photo['categoryLabel'] }}</span>
                                <h2>{{ $photo['title'] }}</h2>
                                <div class="photo-gallery-card-footer">
                                    <span class="photo-gallery-views">
                                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                                        {{ number_format($photo['views']) }} views
                                    </span>
                                    <button class="photo-gallery-view" type="button"
                                        onclick="openModal(this.closest('.photo-gallery-card'))">
                                        View <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <p class="photo-gallery-empty" id="photoGalleryEmpty" hidden>
                    No photos match your search. Try another title or category.
                </p>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <div class="gallery-modal" id="galleryModal" onclick="closeModal(event)">
        <div class="gallery-modal-content">
            <button class="gallery-modal-close" type="button" aria-label="Close photo" onclick="closeModal(event)">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
            <img id="modalImage" src="" alt="">
            <p class="gallery-modal-caption" id="modalTitle"></p>
        </div>
    </div>

    <script>
        const galleryGrid = document.getElementById('photoGalleryGrid');
        const galleryCards = Array.from(galleryGrid.querySelectorAll('.photo-gallery-card'));
        const gallerySearch = document.getElementById('photoGallerySearch');
        const galleryCategory = document.getElementById('photoGalleryCategory');
        const gallerySort = document.getElementById('photoGallerySort');
        const galleryCount = document.getElementById('photoGalleryCount');
        const galleryEmpty = document.getElementById('photoGalleryEmpty');

        function updateGallery() {
            const searchTerm = gallerySearch.value.trim().toLowerCase();
            const selectedCategory = galleryCategory.value;
            const matchingCards = galleryCards.filter((card) => {
                const matchesCategory = selectedCategory === 'all' || card.dataset.category === selectedCategory;
                const matchesSearch = card.dataset.search.toLowerCase().includes(searchTerm);

                return matchesCategory && matchesSearch;
            });

            if (gallerySort.value === 'title') {
                matchingCards.sort((first, second) => first.dataset.title.localeCompare(second.dataset.title));
            } else if (gallerySort.value === 'views') {
                matchingCards.sort((first, second) => Number(second.dataset.views) - Number(first.dataset.views));
            }

            galleryCards.forEach((card) => {
                card.hidden = !matchingCards.includes(card);
            });
            matchingCards.forEach((card) => galleryGrid.append(card));

            const photoWord = matchingCards.length === 1 ? 'photo' : 'photos';
            galleryCount.textContent = `${matchingCards.length} ${photoWord}`;
            galleryEmpty.hidden = matchingCards.length > 0;
        }

        function resetGallery() {
            gallerySearch.value = '';
            galleryCategory.value = 'all';
            gallerySort.value = 'featured';
            updateGallery();
        }

        gallerySearch.addEventListener('input', updateGallery);
        galleryCategory.addEventListener('change', updateGallery);
        gallerySort.addEventListener('change', updateGallery);
        document.getElementById('photoGalleryReset').addEventListener('click', resetGallery);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                const modal = document.getElementById('galleryModal');
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        function openModal(element) {
            const modal = document.getElementById('galleryModal');
            const title = element.getAttribute('data-title');
            const image = element.querySelector('.gallery-photo');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = image.src;
            modalImage.alt = image.alt;
            document.getElementById('modalTitle').textContent = title;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(event) {
            if (event.target.classList.contains('gallery-modal') || event.target.closest('.gallery-modal-close')) {
                const modal = document.getElementById('galleryModal');
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }
    </script>
</body>
</html>
