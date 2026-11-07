<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage the images shown in the FEC International School homepage hero slideshow">
    <title>Manage Hero Images | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-hero-management.css') }}">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container hero-management">
        <header class="hero-management-heading">
            <div>
                <a class="hero-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <h1>Home Page Hero Images</h1>
                <p>Manage the images displayed in the homepage slideshow.</p>
            </div>
            <span class="hero-image-count">{{ $slides->count() }} {{ $slides->count() === 1 ? 'image' : 'images' }}</span>
        </header>

        <p class="hero-management-notice" role="note">
            <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
            Use Enable or Disable to control which images appear on the homepage. At least one image and one enabled image must remain.
        </p>
        @if (session('status'))
            <p class="hero-management-notice success" role="status">{{ session('status') }}</p>
        @endif
        @if ($errors->has('hero_slide'))
            <p class="hero-management-notice warning" role="alert">{{ $errors->first('hero_slide') }}</p>
        @endif
        @if ($errors->any() && ! $errors->has('hero_slide'))
            <div class="hero-management-notice warning" role="alert">
                <strong>Please review the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="hero-upload-panel" aria-labelledby="hero-upload-title">
            <div class="hero-management-section-heading">
                <span class="hero-management-icon"><i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i></span>
                <div>
                    <h2 id="hero-upload-title">Add a hero image</h2>
                    <p>Upload a JPG, PNG, or WebP image up to 5 MB.</p>
                </div>
            </div>
            <form class="hero-upload-form" action="{{ route('admin.hero.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="hero-field">
                    <span>Image file</span>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required>
                </label>
                <label class="hero-field">
                    <span>Image description</span>
                    <input type="text" name="alt_text" maxlength="255" value="{{ old('alt_text') }}" placeholder="Describe the image" required>
                </label>
                <button class="hero-button primary" type="submit">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Add image
                </button>
            </form>
        </section>

        <section class="hero-slides-section" aria-labelledby="hero-slides-title">
            <div class="hero-management-section-heading">
                <span class="hero-management-icon"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <div>
                    <h2 id="hero-slides-title">Current slideshow</h2>
                    <p>Edit slide details, control homepage visibility, or remove slides.</p>
                </div>
            </div>

            <div class="hero-filter-bar" role="search" aria-label="Filter hero images">
                <label class="hero-filter-search">
                    <span class="sr-only">Search images</span>
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" id="hero-image-search" placeholder="Search by description or filename" autocomplete="off">
                </label>
                <label class="hero-filter-status">
                    <span>Status</span>
                    <select id="hero-image-status">
                        <option value="all">All images</option>
                        <option value="enabled">Enabled</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </label>
                <label class="hero-filter-sort">
                    <span>Sort by</span>
                    <select id="hero-image-sort">
                        <option value="asc">Display order</option>
                        <option value="desc">Reverse display order</option>
                    </select>
                </label>
                <p class="hero-filter-count" id="hero-filter-count" aria-live="polite"></p>
            </div>

            <div class="hero-slide-list" id="hero-slide-list">
                @foreach ($slides as $slide)
                    <article class="hero-slide-card" data-search="{{ strtolower($slide->alt_text . ' ' . basename($slide->image_url)) }}" data-order="{{ $slide->sort_order }}" data-status="{{ $slide->is_enabled ? 'enabled' : 'disabled' }}">
                        <div class="hero-slide-preview">
                            <img src="{{ $slide->image_url }}" alt="{{ $slide->alt_text }}">
                            <span class="hero-slide-order">#{{ $loop->iteration }}</span>
                        </div>
                        <div class="hero-slide-details">
                            <form class="hero-slide-edit" action="{{ route('admin.hero.update', $slide) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <label class="hero-field hero-description-field">
                                    <span>Image description</span>
                                    <input type="text" name="alt_text" value="{{ $slide->alt_text }}" maxlength="255" required>
                                </label>
                                <label class="hero-field hero-replace-field">
                                    <span>Replace image <small>(optional)</small></span>
                                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                                </label>
                                <label class="hero-field hero-order-field">
                                    <span>Display order</span>
                                    <input type="number" name="sort_order" value="{{ $slide->sort_order }}" min="0" max="10000" required>
                                </label>
                                <span class="hero-slide-status {{ $slide->is_enabled ? 'enabled' : 'disabled' }}">
                                    <i class="fa-solid {{ $slide->is_enabled ? 'fa-circle-check' : 'fa-circle-minus' }}" aria-hidden="true"></i>
                                    {{ $slide->is_enabled ? 'Enabled on homepage' : 'Disabled on homepage' }}
                                </span>
                                <button class="hero-button primary" type="submit">
                                    <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save changes
                                </button>
                            </form>
                            <div class="hero-slide-actions">
                                <form class="hero-slide-toggle" action="{{ route('admin.hero.toggle', $slide) }}" method="POST">
                                    @csrf
                                    <button class="hero-button {{ $slide->is_enabled ? 'danger' : 'success' }}" type="submit">
                                        <i class="fa-solid {{ $slide->is_enabled ? 'fa-toggle-off' : 'fa-toggle-on' }}" aria-hidden="true"></i>
                                        {{ $slide->is_enabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <form class="hero-slide-delete" action="{{ route('admin.hero.destroy', $slide) }}" method="POST" onsubmit="return confirm('Delete this hero image? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="hero-button danger" type="submit">
                                        <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <p class="hero-filter-empty" id="hero-filter-empty" hidden>No hero images match your search.</p>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const search = document.getElementById('hero-image-search');
            const status = document.getElementById('hero-image-status');
            const sort = document.getElementById('hero-image-sort');
            const list = document.getElementById('hero-slide-list');
            const count = document.getElementById('hero-filter-count');
            const empty = document.getElementById('hero-filter-empty');
            const cards = Array.from(list.querySelectorAll('.hero-slide-card'));

            const updateSlides = () => {
                const query = search.value.trim().toLocaleLowerCase();
                const direction = sort.value === 'desc' ? -1 : 1;
                const matchingCards = cards.filter((card) =>
                    card.dataset.search.includes(query)
                    && (status.value === 'all' || card.dataset.status === status.value)
                );

                matchingCards.sort((first, second) =>
                    (Number(first.dataset.order) - Number(second.dataset.order)) * direction
                );

                matchingCards.forEach((card) => list.appendChild(card));
                cards.filter((card) => !matchingCards.includes(card)).forEach((card) => {
                    card.hidden = true;
                });
                matchingCards.forEach((card) => {
                    card.hidden = false;
                });

                count.textContent = `Showing ${matchingCards.length} of ${cards.length} images`;
                empty.hidden = matchingCards.length !== 0;
            };

            search.addEventListener('input', updateSlides);
            status.addEventListener('change', updateSlides);
            sort.addEventListener('change', updateSlides);
            updateSlides();
        });
    </script>

    @include('partials.footer')
</body>
</html>
