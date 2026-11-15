<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage videos shown in the FEC International School video gallery">
    <title>Manage Video Gallery | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-gallery-photo-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-gallery-video-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-delete-confirmation.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container gallery-management gallery-video-management">
        <header class="gallery-management-heading">
            <div>
                <a class="gallery-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <p class="gallery-management-eyebrow">CONTENT STUDIO</p>
                <h1>Video Gallery</h1>
                <p>Manage the videos visitors can browse across school events, campus life, and academics.</p>
            </div>
            <div class="gallery-management-total">
                <strong>{{ $videos->count() }}</strong>
                <span>{{ $videos->count() === 1 ? 'Video' : 'Videos' }} in gallery</span>
            </div>
        </header>

        @if (session('status'))
            <p class="gallery-management-notice success" role="status">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i> {{ session('status') }}
            </p>
        @endif
        @if ($errors->any())
            <div class="gallery-management-notice error" role="alert">
                <strong>Please review the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="gallery-management-panel" aria-labelledby="gallery-video-create-title">
            <div class="gallery-panel-heading">
                <span class="gallery-panel-icon"><i class="fa-solid fa-circle-play" aria-hidden="true"></i></span>
                <div>
                    <h2 id="gallery-video-create-title">Add a video</h2>
                    <p>Use an 11-character YouTube video ID. The thumbnail is generated automatically.</p>
                </div>
            </div>
            <form class="gallery-upload-form video-upload-form" action="{{ route('admin.gallery.videos.store') }}" method="POST">
                @csrf
                <label class="gallery-field">
                    <span>Video title</span>
                    <input type="text" name="title" value="{{ old('title') }}" maxlength="150" placeholder="e.g. Annual Sports Day" required>
                </label>
                <label class="gallery-field">
                    <span>Category</span>
                    <select name="category" required>
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" @selected(old('category', 'events') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="gallery-field">
                    <span>YouTube video ID</span>
                    <input type="text" name="youtube_id" value="{{ old('youtube_id') }}" minlength="11" maxlength="11" pattern="[A-Za-z0-9_-]{11}" placeholder="e.g. s9xq0rOPltg" required>
                </label>
                <label class="gallery-field">
                    <span>Duration</span>
                    <input type="text" name="duration" value="{{ old('duration') }}" maxlength="30" placeholder="e.g. 8:45 or Short" required>
                </label>
                <button class="gallery-button primary" type="submit">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Add video
                </button>
            </form>
        </section>

        <section class="gallery-management-panel" aria-labelledby="gallery-video-library-title">
            <div class="gallery-panel-heading">
                <span class="gallery-panel-icon"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <div>
                    <h2 id="gallery-video-library-title">Video library</h2>
                    <p>Update video details, category, display order, or YouTube ID.</p>
                </div>
            </div>

            <div class="gallery-filter-bar" role="search" aria-label="Filter gallery videos">
                <label class="gallery-search">
                    <span class="sr-only">Search videos</span>
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" id="gallery-video-search" placeholder="Search title or category" autocomplete="off">
                </label>
                <label class="gallery-filter-field">
                    <span>Category</span>
                    <select id="gallery-video-category">
                        <option value="all">All categories</option>
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="gallery-filter-field">
                    <span>Sort by</span>
                    <select id="gallery-video-sort">
                        <option value="order">Display order</option>
                        <option value="title">Title (A–Z)</option>
                    </select>
                </label>
                <p class="gallery-filter-count" id="gallery-video-count" aria-live="polite"></p>
            </div>

            <div class="gallery-photo-grid gallery-video-grid" id="gallery-video-grid">
                @foreach ($videos as $video)
                    <article class="gallery-photo-card gallery-video-card"
                        data-title="{{ $video->title }}"
                        data-search="{{ $video->title }} {{ $video->category_label }}"
                        data-category="{{ $video->category }}"
                        data-order="{{ $video->sort_order }}">
                        <div class="gallery-photo-preview gallery-video-preview">
                            @if ($video->thumbnail_url)
                                <img src="{{ $video->thumbnail_url }}" alt="Thumbnail for {{ $video->title }}">
                            @else
                                <div class="gallery-video-no-thumbnail">
                                    <i class="fa-regular fa-circle-play" aria-hidden="true"></i>
                                    <span>YouTube ID not set</span>
                                </div>
                            @endif
                            <span class="gallery-photo-category">{{ $video->category_label }}</span>
                        </div>
                        <form id="edit-gallery-video-{{ $video->id }}" class="gallery-photo-edit" action="{{ route('admin.gallery.videos.update', $video) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label class="gallery-field">
                                <span>Video title</span>
                                <input type="text" name="title" value="{{ $video->title }}" maxlength="150" required>
                            </label>
                            <label class="gallery-field">
                                <span>Category</span>
                                <select name="category" required>
                                    @foreach ($categories as $value => $label)
                                        <option value="{{ $value }}" @selected($video->category === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="gallery-field">
                                <span>YouTube video ID <small>(leave blank to keep as a placeholder)</small></span>
                                <input type="text" name="youtube_id" value="{{ $video->youtube_id }}" minlength="11" maxlength="11" pattern="[A-Za-z0-9_-]{11}" placeholder="11-character ID">
                            </label>
                            <div class="gallery-edit-row gallery-video-edit-row">
                                <label class="gallery-field">
                                    <span>Duration</span>
                                    <input type="text" name="duration" value="{{ $video->duration }}" maxlength="30" required>
                                </label>
                                <label class="gallery-field gallery-order-field">
                                    <span>Order</span>
                                    <input type="number" name="sort_order" value="{{ $video->sort_order }}" min="0" max="10000" required>
                                </label>
                            </div>
                        </form>
                        <div class="gallery-photo-actions">
                            <button class="gallery-button primary" type="submit" form="edit-gallery-video-{{ $video->id }}">
                                <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save changes
                            </button>
                            <form data-delete-confirmation data-delete-type="video" data-delete-name="{{ $video->title }}" action="{{ route('admin.gallery.videos.destroy', $video) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="gallery-button danger" type="submit">
                                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
            <p class="gallery-filter-empty" id="gallery-video-empty" hidden>No videos match your filters.</p>
        </section>

        @include('admin.partials.delete-confirmation')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const search = document.getElementById('gallery-video-search');
            const category = document.getElementById('gallery-video-category');
            const sort = document.getElementById('gallery-video-sort');
            const grid = document.getElementById('gallery-video-grid');
            const count = document.getElementById('gallery-video-count');
            const empty = document.getElementById('gallery-video-empty');
            const cards = Array.from(grid.querySelectorAll('.gallery-video-card'));

            const updateVideos = () => {
                const query = search.value.trim().toLocaleLowerCase();
                const matchingCards = cards.filter((card) =>
                    card.dataset.search.toLocaleLowerCase().includes(query)
                    && (category.value === 'all' || card.dataset.category === category.value)
                );

                if (sort.value === 'title') {
                    matchingCards.sort((first, second) => first.dataset.title.localeCompare(second.dataset.title));
                } else {
                    matchingCards.sort((first, second) => Number(first.dataset.order) - Number(second.dataset.order));
                }

                cards.forEach((card) => {
                    card.hidden = !matchingCards.includes(card);
                });
                matchingCards.forEach((card) => grid.append(card));
                count.textContent = `Showing ${matchingCards.length} of ${cards.length} videos`;
                empty.hidden = matchingCards.length !== 0;
            };

            search.addEventListener('input', updateVideos);
            category.addEventListener('change', updateVideos);
            sort.addEventListener('change', updateVideos);
            updateVideos();
        });
    </script>

    @include('partials.footer')
    <script src="{{ asset('js/admin-delete-confirmation.js') }}" defer></script>
</body>
</html>
