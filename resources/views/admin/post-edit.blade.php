<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit a school news post, announcement, or event">
    <title>Edit Post | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-post-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container post-management">
        <header class="post-management-heading">
            <div class="post-management-heading-copy">
                <a class="post-management-back" href="{{ route('admin.posts.index') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> News &amp; Events Management
                </a>
                <p class="post-management-eyebrow">CONTENT STUDIO</p>
                <h1>Edit post</h1>
                <p>Update this post and its public listing.</p>
            </div>
        </header>

        @if ($errors->any())
            <div class="post-management-notice error" role="alert">
                <strong>Please review the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="post-management-panel" aria-labelledby="post-edit-title">
            <div class="post-panel-heading">
                <span class="post-panel-icon"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i></span>
                <div>
                    <h2 id="post-edit-title">{{ $post->title }}</h2>
                    <p>Saved changes will appear on the public News &amp; Events pages immediately.</p>
                </div>
            </div>
            <form class="post-form" action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.partials.post-fields', ['post' => $post])
                <div class="post-form-actions post-field-wide">
                    <a class="post-button secondary" href="{{ route('admin.posts.index') }}">Cancel</a>
                    <button class="post-button primary" type="submit">
                        <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save changes
                    </button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
