<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My News &amp; Event Submissions | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-post-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account-content-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container post-management account-content-management">
        <header class="post-management-heading">
            <div class="post-management-heading-copy">
                <a class="post-management-back" href="{{ route('account.dashboard') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> My Dashboard</a>
                <p class="post-management-eyebrow">MY SUBMISSIONS</p>
                <h1>News &amp; Events</h1>
                <p>Submit stories and event posts for administrator review before publication.</p>
            </div>
            <span class="post-management-count"><strong>{{ $posts->count() }}</strong> {{ $posts->count() === 1 ? 'Submission' : 'Submissions' }}</span>
        </header>

        @if (session('status'))
            <p class="post-management-notice success" role="status">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <div class="post-management-notice error" role="alert">
                <strong>Please review the following:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="post-management-panel">
            <div class="post-panel-heading"><span class="post-panel-icon"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></span><div><h2>Submit a News/Event post</h2><p>Only News and Event posts can be submitted from this page.</p></div></div>
            <form class="post-form" action="{{ route('account.content.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="post-field"><span>Title <b class="post-required">*</b></span><input type="text" name="title" value="{{ old('title') }}" maxlength="255" required></label>
                <label class="post-field">
                    <span>Type <b class="post-required">*</b></span>
                    <select name="category" required>
                        <option value="News" @selected(old('category') === 'News')>News</option>
                        <option value="Event" @selected(old('category') === 'Event')>Event</option>
                    </select>
                </label>
                <label class="post-field"><span>Publication date <b class="post-required">*</b></span><input type="datetime-local" name="published_at" value="{{ old('published_at') }}" required></label>
                <label class="post-field"><span>Image <small>(optional, JPG/PNG/WebP up to 5 MB)</small></span><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></label>
                <label class="post-field post-field-wide"><span>Image description <b class="post-required">*</b></span><input type="text" name="alt_text" value="{{ old('alt_text') }}" maxlength="255" required></label>
                <label class="post-field post-field-wide"><span>Short summary <b class="post-required">*</b></span><textarea name="excerpt" maxlength="500" rows="3" required>{{ old('excerpt') }}</textarea></label>
                <label class="post-field post-field-wide"><span>Full content <b class="post-required">*</b><small> Line breaks are preserved when published.</small></span><textarea name="body" rows="8" required>{{ old('body') }}</textarea></label>
                <label class="post-field post-field-wide"><span>location <small>(optional; for events)</small></span><input type="text" name="location" value="{{ old('location') }}" maxlength="255"></label>
                <div class="post-form-actions post-field-wide"><p><b class="post-required">*</b> Required fields</p><button class="post-button primary" type="submit"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Submit for review</button></div>
            </form>
        </section>

        <section class="post-management-panel">
            <div class="post-panel-heading"><span class="post-panel-icon"><i class="fa-solid fa-list-check" aria-hidden="true"></i></span><div><h2>My News/Event submissions</h2><p>Editing a published submission sends it back for review.</p></div></div>
            <div class="account-post-list">
                @forelse ($posts as $post)
                    <details class="account-post-item">
                        <summary>
                            <span><strong>{{ $post->title }}</strong><small>{{ $post->category }} · {{ $post->published_at->format('M d, Y') }}</small></span>
                            <span class="account-content-status {{ $post->is_approved ? 'approved' : 'pending' }}">{{ $post->is_approved ? 'Approved' : 'Awaiting review' }}</span>
                        </summary>
                        <form class="post-form" action="{{ route('account.content.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label class="post-field"><span>Title</span><input type="text" name="title" value="{{ $post->title }}" maxlength="255" required></label>
                            <label class="post-field">
                                <span>Type</span>
                                <select name="category" required>
                                    <option value="News" @selected($post->category === 'News')>News</option>
                                    <option value="Event" @selected($post->category === 'Event')>Event</option>
                                </select>
                            </label>
                            <label class="post-field"><span>Publication date</span><input type="datetime-local" name="published_at" value="{{ $post->published_at->format('Y-m-d\TH:i') }}" required></label>
                            <label class="post-field"><span>Replace image <small>(optional)</small></span><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></label>
                            <label class="post-field post-field-wide"><span>Alternative text</span><input type="text" name="alt_text" value="{{ $post->alt_text }}" maxlength="255" required></label>
                            <label class="post-field post-field-wide"><span>Excerpt</span><textarea name="excerpt" maxlength="500" rows="3" required>{{ $post->excerpt }}</textarea></label>
                            <label class="post-field post-field-wide"><span>Post content</span><textarea name="body" rows="8" required>{{ $post->body }}</textarea></label>
                            <label class="post-field post-field-wide"><span>Event location <small>(optional)</small></span><input type="text" name="location" value="{{ $post->location }}" maxlength="255"></label>
                            <div class="post-form-actions post-field-wide">
                                <button class="post-button primary" type="submit"><i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save and resubmit</button>
                                <button class="post-button danger" type="submit" form="delete-post-{{ $post->id }}"><i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete</button>
                            </div>
                        </form>
                        <form id="delete-post-{{ $post->id }}" action="{{ route('account.content.posts.destroy', $post) }}" method="POST">@csrf @method('DELETE')</form>
                    </details>
                @empty
                    <p>You have not submitted any News/Event posts yet.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
