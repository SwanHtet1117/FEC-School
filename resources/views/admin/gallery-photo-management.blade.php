<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage photos shown in the FEC International School public gallery">
    <title>Manage Photo Gallery | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-content-approval.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-gallery-photo-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-delete-confirmation.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container gallery-management">
        <header class="gallery-management-heading">
            <div>
                <a class="gallery-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <p class="gallery-management-eyebrow">CONTENT STUDIO</p>
                <h1>Photo Gallery</h1>
                <p>Curate the photos visitors see across school events, campus life, and academics.</p>
            </div>
            <div class="gallery-management-total">
                <strong>{{ $photos->count() }}</strong>
                <span>{{ $photos->count() === 1 ? 'Photo' : 'Photos' }} in gallery</span>
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

        <section class="gallery-management-panel" aria-labelledby="gallery-upload-title">
            <div class="gallery-panel-heading">
                <span class="gallery-panel-icon"><i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i></span>
                <div>
                    <h2 id="gallery-upload-title">Add a photo</h2>
                    <p>Upload a JPG, PNG, or WebP image up to 5 MB.</p>
                </div>
            </div>
            <form class="gallery-upload-form" action="{{ route('admin.gallery.photos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="gallery-field">
                    <span>Image file</span>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required>
                </label>
                <label class="gallery-field">
                    <span>Photo title</span>
                    <input type="text" name="title" value="{{ old('title') }}" maxlength="150" placeholder="e.g. Science fair 2026" required>
                </label>
                <label class="gallery-field">
                    <span>Category</span>
                    <select name="category" required>
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" @selected(old('category', 'events') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="gallery-field gallery-field-wide">
                    <span>Alternative text <small>Describe the photo for accessibility</small></span>
                    <input type="text" name="alt_text" value="{{ old('alt_text') }}" maxlength="255" placeholder="Describe what is happening in the image" required>
                </label>
                <button class="gallery-button primary" type="submit">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Add to gallery
                </button>
            </form>
        </section>

        <section class="gallery-management-panel" aria-labelledby="gallery-library-title">
            <div class="gallery-panel-heading">
                <span class="gallery-panel-icon"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <div>
                    <h2 id="gallery-library-title">Gallery library</h2>
                    <p>Edit a photo’s details, change its order, replace the image, or remove it.</p>
                </div>
            </div>

            <div class="gallery-filter-bar" role="search" aria-label="Filter gallery photos">
                <label class="gallery-search">
                    <span class="sr-only">Search photos</span>
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" id="gallery-photo-search" placeholder="Search title or description" autocomplete="off">
                </label>
                <label class="gallery-filter-field">
                    <span>Category</span>
                    <select id="gallery-photo-category">
                        <option value="all">All categories</option>
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="gallery-filter-field">
                    <span>Sort by</span>
                    <select id="gallery-photo-sort">
                        <option value="order">Display order</option>
                        <option value="title">Title (A–Z)</option>
                    </select>
                </label>
                <p class="gallery-filter-count" id="gallery-photo-count" aria-live="polite"></p>
            </div>

            <div class="gallery-photo-grid" id="gallery-photo-grid">
                @foreach ($photos as $photo)
                    <article class="gallery-photo-card"
                        data-title="{{ $photo->title }}"
                        data-search="{{ $photo->title }} {{ $photo->category_label }} {{ $photo->alt_text }}"
                        data-category="{{ $photo->category }}"
                        data-order="{{ $photo->sort_order }}">
                        <div class="gallery-photo-preview">
                            <img src="{{ $photo->image_url }}" alt="{{ $photo->alt_text }}">
                            <span class="gallery-photo-category">{{ $photo->category_label }}</span>
                        </div>
                        <div class="content-approval-badge {{ $photo->is_approved ? 'approved' : 'pending' }}">
                            {{ $photo->is_approved ? 'Approved' : 'Pending review' }}
                        </div>
                        <form id="edit-gallery-photo-{{ $photo->id }}" class="gallery-photo-edit" action="{{ route('admin.gallery.photos.update', $photo) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label class="gallery-field">
                                <span>Photo title</span>
                                <input type="text" name="title" value="{{ $photo->title }}" maxlength="150" required>
                            </label>
                            <label class="gallery-field">
                                <span>Category</span>
                                <select name="category" required>
                                    @foreach ($categories as $value => $label)
                                        <option value="{{ $value }}" @selected($photo->category === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="gallery-field">
                                <span>Alternative text</span>
                                <input type="text" name="alt_text" value="{{ $photo->alt_text }}" maxlength="255" required>
                            </label>
                            <div class="gallery-edit-row">
                                <label class="gallery-field gallery-order-field">
                                    <span>Display order</span>
                                    <input type="number" name="sort_order" value="{{ $photo->sort_order }}" min="0" max="10000" required>
                                </label>
                                <label class="gallery-field gallery-replace-field">
                                    <span>Replace photo <small>(optional)</small></span>
                                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                                </label>
                            </div>
                        </form>
                        <div class="gallery-photo-actions">
                            <button class="gallery-button primary" type="submit" form="edit-gallery-photo-{{ $photo->id }}">
                                <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save changes
                            </button>
                            @unless ($photo->is_approved)
                                <form action="{{ route('admin.gallery.photos.approve', $photo) }}" method="POST">
                                    @csrf
                                    <button class="gallery-button primary" type="submit"><i class="fa-solid fa-check" aria-hidden="true"></i> Approve</button>
                                </form>
                            @endunless
                            <form data-delete-confirmation data-delete-type="photo" data-delete-name="{{ $photo->title }}" action="{{ route('admin.gallery.photos.destroy', $photo) }}" method="POST">
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
            <p class="gallery-filter-empty" id="gallery-photo-empty" hidden>No photos match your filters.</p>
        </section>

        @include('admin.partials.delete-confirmation')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const search = document.getElementById('gallery-photo-search');
            const category = document.getElementById('gallery-photo-category');
            const sort = document.getElementById('gallery-photo-sort');
            const grid = document.getElementById('gallery-photo-grid');
            const count = document.getElementById('gallery-photo-count');
            const empty = document.getElementById('gallery-photo-empty');
            const cards = Array.from(grid.querySelectorAll('.gallery-photo-card'));

            const updatePhotos = () => {
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
                count.textContent = `Showing ${matchingCards.length} of ${cards.length} photos`;
                empty.hidden = matchingCards.length !== 0;
            };

            search.addEventListener('input', updatePhotos);
            category.addEventListener('change', updatePhotos);
            sort.addEventListener('change', updatePhotos);
            updatePhotos();
        });
    </script>

    @include('partials.footer')
    <script src="{{ asset('js/admin-delete-confirmation.js') }}" defer></script>
</body>
</html>
