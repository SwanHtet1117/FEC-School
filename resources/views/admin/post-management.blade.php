<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage school news, announcements, and events">
    <title>News, Announcements &amp; Events | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-post-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-delete-confirmation.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container post-management">
        <header class="post-management-heading">
            <div class="post-management-heading-copy">
                <a class="post-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <p class="post-management-eyebrow">CONTENT STUDIO</p>
                <h1>News, Announcements &amp; Events</h1>
                <p>Keep your school community informed with fresh stories and updates.</p>
            </div>
            <div class="post-management-heading-actions">
                <a class="post-button secondary" href="{{ route('admin.events.calendar') }}">
                    <i class="fa-regular fa-calendar-days" aria-hidden="true"></i> Event Calendar
                </a>
                <span class="post-management-count">
                    <strong>{{ $postCount }}</strong>
                    {{ $postCount === 1 ? 'Published post' : 'Published posts' }}
                </span>
            </div>
        </header>

        @if (session('status'))
            <p class="post-management-notice success" role="status">{{ session('status') }}</p>
        @endif
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

        <section class="post-management-stats" aria-label="Post totals">
            @foreach (['News', 'Announcement', 'Event'] as $category)
                <a class="post-stat-card {{ \Illuminate\Support\Str::slug($category) }}"
                    href="{{ route('admin.posts.index', ['category' => $category]) }}"
                    aria-label="Filter posts by {{ $category }}">
                    <span class="post-stat-icon" aria-hidden="true">
                        @if ($category === 'Event')
                            <i class="fa-regular fa-calendar-days"></i>
                        @elseif ($category === 'Announcement')
                            <i class="fa-solid fa-bullhorn"></i>
                        @else
                            <i class="fa-regular fa-newspaper"></i>
                        @endif
                    </span>
                    <div class="post-stat-copy">
                        <span>{{ $category === 'Event' ? 'Events' : ($category === 'Announcement' ? 'Announcements' : $category) }}</span>
                        <strong>{{ $categoryCounts[$category] ?? 0 }}</strong>
                        <small>Published on your site</small>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square post-stat-link" aria-hidden="true"></i>
                </a>
            @endforeach
        </section>

        <section class="post-management-panel" aria-labelledby="post-create-title">
            <div class="post-panel-heading">
                <span class="post-panel-icon"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></span>
                <div>
                    <h2 id="post-create-title">Create a post</h2>
                    <p>Share a story, important announcement, or upcoming event with your community.</p>
                </div>
            </div>
            <form class="post-form" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.partials.post-fields', ['post' => null])
                <div class="post-form-actions post-field-wide">
                    <p><span class="post-required" aria-hidden="true">*</span> Required fields</p>
                    <button class="post-button primary" type="submit">
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Publish post
                    </button>
                </div>
            </form>
        </section>

        <section class="post-management-panel" aria-labelledby="post-list-title">
            <div class="post-panel-heading">
                <span class="post-panel-icon"><i class="fa-solid fa-list-check" aria-hidden="true"></i></span>
                <div>
                    <h2 id="post-list-title">Published posts</h2>
                    <p>Search, filter, and update the content currently available on the public site.</p>
                </div>
            </div>

            <form class="post-filter-form" action="{{ route('admin.posts.index') }}" method="GET">
                <label class="post-field post-filter-search">
                    <span>Search posts</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="Title or content">
                </label>
                <label class="post-field">
                    <span>Category</span>
                    <select name="category">
                        <option value="" @selected(blank($filters['category'] ?? null))>All categories</option>
                        @foreach (['News', 'Announcement', 'Event'] as $category)
                            <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category === 'Event' ? 'Events' : $category }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="post-filter-actions">
                    <button class="post-button primary" type="submit"><i class="fa-solid fa-filter" aria-hidden="true"></i> Filter</button>
                    @if (collect($filters)->contains(fn ($value) => filled($value)))
                        <a class="post-button secondary" href="{{ route('admin.posts.index') }}">Clear</a>
                    @endif
                </div>
            </form>

            <div class="post-table-wrap">
                <table class="post-table">
                    <thead>
                        <tr>
                            <th scope="col">Post</th>
                            <th scope="col">Category</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                            <tr>
                                <td>
                                    <strong>{{ $post->title }}</strong>
                                    <small>{{ \Illuminate\Support\Str::limit($post->excerpt, 140) }}</small>
                                </td>
                                <td><span class="post-category {{ \Illuminate\Support\Str::slug($post->category) }}">{{ $post->category }}</span></td>
                                <td>{{ $post->published_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="post-row-actions">
                                        <a class="post-button secondary" href="{{ route('admin.posts.edit', $post) }}">
                                            <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i> Edit
                                        </a>
                                        <form data-delete-confirmation data-delete-type="post" data-delete-name="{{ $post->title }}" action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="post-button danger" type="submit">
                                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="post-empty" colspan="4">No posts match your filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($posts->hasPages())
                <nav class="post-pagination" aria-label="Post pages">
                    <p>Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }} posts</p>
                    <div>
                        @if ($posts->previousPageUrl())
                            <a class="post-button secondary" href="{{ $posts->previousPageUrl() }}" rel="prev">Previous</a>
                        @else
                            <span class="post-button disabled" aria-disabled="true">Previous</span>
                        @endif
                        <span class="post-pagination-page">Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</span>
                        @if ($posts->nextPageUrl())
                            <a class="post-button secondary" href="{{ $posts->nextPageUrl() }}" rel="next">Next</a>
                        @else
                            <span class="post-button disabled" aria-disabled="true">Next</span>
                        @endif
                    </div>
                </nav>
            @endif
        </section>

        @include('admin.partials.delete-confirmation')
    </main>

    @include('partials.footer')
    <script src="{{ asset('js/admin-delete-confirmation.js') }}" defer></script>
</body>
</html>
