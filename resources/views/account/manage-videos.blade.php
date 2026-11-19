<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Video Submissions | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-gallery-photo-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-gallery-video-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account-content-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container gallery-management gallery-video-management account-content-management">
        <header class="gallery-management-heading">
            <div>
                <a class="gallery-management-back" href="{{ route('account.dashboard') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> My Dashboard</a>
                <p class="gallery-management-eyebrow">MY SUBMISSIONS</p>
                <h1>Video Gallery</h1>
                <p>Videos are kept private until an administrator approves them.</p>
            </div>
            <div class="gallery-management-total"><strong>{{ $videos->count() }}</strong><span>{{ $videos->count() === 1 ? 'Submission' : 'Submissions' }}</span></div>
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
            <div class="gallery-panel-heading"><span class="gallery-panel-icon"><i class="fa-solid fa-circle-play" aria-hidden="true"></i></span><div><h2>Submit a video</h2><p>Provide an 11-character YouTube video ID.</p></div></div>
            <form class="gallery-upload-form video-upload-form" action="{{ route('account.content.videos.store') }}" method="POST">
                @csrf
                <label class="gallery-field"><span>Video title</span><input type="text" name="title" value="{{ old('title') }}" maxlength="150" required></label>
                <label class="gallery-field">
                    <span>Category</span>
                    <select name="category" required>
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" @selected(old('category', 'events') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="gallery-field"><span>YouTube video ID</span><input type="text" name="youtube_id" value="{{ old('youtube_id') }}" minlength="11" maxlength="11" pattern="[A-Za-z0-9_-]{11}" required></label>
                <label class="gallery-field"><span>Duration</span><input type="text" name="duration" value="{{ old('duration') }}" maxlength="30" placeholder="e.g. 8:45" required></label>
                <button class="gallery-button primary" type="submit"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Submit for review</button>
            </form>
        </section>

        <section class="gallery-management-panel">
            <div class="gallery-panel-heading"><span class="gallery-panel-icon"><i class="fa-regular fa-circle-play" aria-hidden="true"></i></span><div><h2>My video submissions</h2><p>Changes to an approved video will be reviewed again before it is shown publicly.</p></div></div>
            <div class="gallery-photo-grid gallery-video-grid">
                @forelse ($videos as $video)
                    <article class="gallery-photo-card gallery-video-card">
                        <div class="gallery-photo-preview gallery-video-preview">
                            @if ($video->thumbnail_url)
                                <img src="{{ $video->thumbnail_url }}" alt="Thumbnail for {{ $video->title }}">
                            @else
                                <div class="gallery-video-no-thumbnail"><i class="fa-regular fa-circle-play" aria-hidden="true"></i><span>Video preview</span></div>
                            @endif
                            <span class="gallery-photo-category">{{ $video->category_label }}</span>
                        </div>
                        <div class="account-content-status {{ $video->is_approved ? 'approved' : 'pending' }}">
                            <i class="fa-solid {{ $video->is_approved ? 'fa-circle-check' : 'fa-clock' }}" aria-hidden="true"></i>
                            {{ $video->is_approved ? 'Approved and published' : 'Awaiting admin review' }}
                        </div>
                        <form class="gallery-photo-edit" action="{{ route('account.content.videos.update', $video) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label class="gallery-field"><span>Video title</span><input type="text" name="title" value="{{ $video->title }}" maxlength="150" required></label>
                            <label class="gallery-field">
                                <span>Category</span>
                                <select name="category" required>
                                    @foreach ($categories as $value => $label)
                                        <option value="{{ $value }}" @selected($video->category === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="gallery-field"><span>YouTube video ID</span><input type="text" name="youtube_id" value="{{ $video->youtube_id }}" minlength="11" maxlength="11" pattern="[A-Za-z0-9_-]{11}" required></label>
                            <label class="gallery-field"><span>Duration</span><input type="text" name="duration" value="{{ $video->duration }}" maxlength="30" required></label>
                            <div class="gallery-photo-actions">
                                <button class="gallery-button primary" type="submit"><i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save and resubmit</button>
                                <button class="gallery-button danger" type="submit" form="delete-video-{{ $video->id }}"><i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete</button>
                            </div>
                        </form>
                        <form id="delete-video-{{ $video->id }}" action="{{ route('account.content.videos.destroy', $video) }}" method="POST">@csrf @method('DELETE')</form>
                    </article>
                @empty
                    <p>You have not submitted any videos yet.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
