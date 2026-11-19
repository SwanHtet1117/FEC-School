<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Submit and manage your homepage hero image submissions">
    <title>My Hero Image Submissions | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-hero-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container hero-management">
        <header class="hero-management-heading">
            <div>
                <a class="hero-management-back" href="{{ route('account.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> My Dashboard
                </a>
                <p class="eyebrow">MY SUBMISSIONS</p>
                <h1>Homepage Hero Images</h1>
                <p>Submit images for the homepage slideshow. An administrator must approve them before publication.</p>
            </div>
            <span class="hero-image-count">{{ $slides->count() }} {{ $slides->count() === 1 ? 'submission' : 'submissions' }}</span>
        </header>

        @if (session('status'))
            <p class="hero-management-notice success" role="status">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
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
                    <h2 id="hero-upload-title">Submit a hero image</h2>
                    <p>Upload a JPG, PNG, or WebP image up to 5 MB.</p>
                </div>
            </div>
            <form class="hero-upload-form" action="{{ route('account.content.hero.store') }}" method="POST" enctype="multipart/form-data">
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
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Submit for review
                </button>
            </form>
        </section>

        <section class="hero-slides-section" aria-labelledby="my-hero-slides-title">
            <div class="hero-management-section-heading">
                <span class="hero-management-icon"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <div>
                    <h2 id="my-hero-slides-title">My submissions</h2>
                    <p>Only submissions awaiting review can be edited or deleted. Published images are managed by an administrator.</p>
                </div>
            </div>

            <div class="hero-slide-list">
                @forelse ($slides as $slide)
                    <article class="hero-slide-card">
                        <div class="hero-slide-preview">
                            <img src="{{ $slide->image_url }}" alt="{{ $slide->alt_text }}">
                        </div>
                        <div class="hero-slide-details">
                            <span class="hero-slide-status {{ $slide->is_approved ? 'enabled' : ($slide->rejected_at ? 'rejected' : 'disabled') }}">
                                <i class="fa-solid {{ $slide->is_approved ? 'fa-circle-check' : ($slide->rejected_at ? 'fa-circle-xmark' : 'fa-clock') }}" aria-hidden="true"></i>
                                {{ $slide->is_approved ? 'Approved - managed by admin' : ($slide->rejected_at ? 'Rejected - edit and resubmit' : 'Awaiting admin review') }}
                            </span>
                            @if (! $slide->is_approved)
                                <form class="hero-slide-edit" action="{{ route('account.content.hero.update', $slide) }}" method="POST" enctype="multipart/form-data">
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
                                    <div class="hero-slide-actions">
                                        <button class="hero-button primary" type="submit">
                                            <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save and resubmit
                                        </button>
                                        <button class="hero-button danger" type="submit" form="delete-hero-slide-{{ $slide->id }}">
                                            <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                        </button>
                                    </div>
                                </form>
                                <form id="delete-hero-slide-{{ $slide->id }}" action="{{ route('account.content.hero.destroy', $slide) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @else
                                <p>{{ $slide->alt_text }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <p>You have not submitted any hero images yet.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
