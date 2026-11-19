<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Photo Submissions | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-gallery-photo-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account-content-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container account-content-management">
        <header class="gallery-management-heading">
            <div>
                <a class="gallery-management-back" href="{{ route('account.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> My Dashboard
                </a>
                <p class="gallery-management-eyebrow">MY SUBMISSIONS</p>
                <h1>Photo Gallery</h1>
                <p>Photos are kept private until an administrator approves them.</p>
            </div>
            <div class="gallery-management-total">
                <strong>{{ $photos->count() }}</strong>
                <span>{{ $photos->count() === 1 ? 'Submission' : 'Submissions' }}</span>
            </div>
        </header>

        @if (session('status'))
            <p class="gallery-management-notice success" role="status">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <div class="gallery-management-notice error" role="alert">
                <strong>Please review the following:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="gallery-management-panel">
            <div class="gallery-panel-heading">
                <span class="gallery-panel-icon"><i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i></span>
                <div><h2>Submit a photo</h2><p>JPG, PNG, or WebP up to 5 MB.</p></div>
            </div>
            <form class="gallery-upload-form" action="{{ route('account.content.photos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="gallery-field"><span>Image file</span><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required></label>
                <label class="gallery-field"><span>Photo title</span><input type="text" name="title" value="{{ old('title') }}" maxlength="150" required></label>
                <label class="gallery-field">
                    <span>Category</span>
                    <select name="category" required>
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" @selected(old('category', 'events') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="gallery-field gallery-field-wide"><span>Alternative text</span><input type="text" name="alt_text" value="{{ old('alt_text') }}" maxlength="255" required></label>
                <button class="gallery-button primary" type="submit"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Submit for review</button>
            </form>
        </section>

        <section class="gallery-management-panel">
            <div class="gallery-panel-heading">
                <span class="gallery-panel-icon"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <div><h2>My photo submissions</h2><p>Changes to an approved photo will be reviewed again before it is shown publicly.</p></div>
            </div>
            <div class="gallery-photo-grid">
                @forelse ($photos as $photo)
                    <article class="gallery-photo-card">
                        <div class="gallery-photo-preview">
                            <img src="{{ $photo->image_url }}" alt="{{ $photo->alt_text }}">
                            <span class="gallery-photo-category">{{ $photo->category_label }}</span>
                        </div>
                        <div class="account-content-status {{ $photo->is_approved ? 'approved' : 'pending' }}">
                            <i class="fa-solid {{ $photo->is_approved ? 'fa-circle-check' : 'fa-clock' }}" aria-hidden="true"></i>
                            {{ $photo->is_approved ? 'Approved and published' : 'Awaiting admin review' }}
                        </div>
                        <form class="gallery-photo-edit" action="{{ route('account.content.photos.update', $photo) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label class="gallery-field"><span>Photo title</span><input type="text" name="title" value="{{ $photo->title }}" maxlength="150" required></label>
                            <label class="gallery-field">
                                <span>Category</span>
                                <select name="category" required>
                                    @foreach ($categories as $value => $label)
                                        <option value="{{ $value }}" @selected($photo->category === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="gallery-field"><span>Alternative text</span><input type="text" name="alt_text" value="{{ $photo->alt_text }}" maxlength="255" required></label>
                            <label class="gallery-field"><span>Replace photo <small>(optional)</small></span><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></label>
                            <div class="gallery-photo-actions">
                                <button class="gallery-button primary" type="submit"><i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save and resubmit</button>
                                <button class="gallery-button danger" type="submit" form="delete-photo-{{ $photo->id }}"><i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete</button>
                            </div>
                        </form>
                        <form id="delete-photo-{{ $photo->id }}" action="{{ route('account.content.photos.destroy', $photo) }}" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    </article>
                @empty
                    <p>You have not submitted any photos yet.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
