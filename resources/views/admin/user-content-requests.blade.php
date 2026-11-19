<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Review all content submissions from FEC International School user accounts">
    <title>User Requests | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-user-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container user-management">
        <header class="user-management-heading">
            <div>
                <a class="user-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <h1>User Requests</h1>
                <p>Review content submitted by user accounts across the school website.</p>
            </div>
            <span class="user-management-count">{{ number_format($summary->total) }} {{ (int) $summary->total === 1 ? 'Request' : 'Requests' }}</span>
        </header>

        <section class="user-management-stats user-content-request-stats" aria-label="User request status totals">
            <div><span>Total requests</span><strong>{{ number_format($summary->total) }}</strong></div>
            <div><span>Pending review</span><strong>{{ number_format($summary->pending) }}</strong></div>
            <div><span>Approved</span><strong>{{ number_format($summary->approved) }}</strong></div>
            <div><span>Rejected</span><strong>{{ number_format($summary->rejected) }}</strong></div>
        </section>

        <section class="user-management-panel" aria-labelledby="request-list-title">
            <div class="user-management-panel-heading">
                <h2 id="request-list-title">All user submissions</h2>
                <p>Open the relevant manager to review or update a submission.</p>
            </div>

            <form class="user-filter-form" action="{{ route('admin.requests.index') }}" method="GET">
                <label class="user-filter-field user-filter-search">
                    <span>Search requests</span>
                    <input type="search" name="search" value="{{ $filters['search'] }}" maxlength="100" placeholder="Request title, user, or email">
                </label>
                <label class="user-filter-field">
                    <span>Request type</span>
                    <select name="type">
                        <option value="">All types</option>
                        <option value="photo" @selected(($filters['type'] ?? '') === 'photo')>Photo</option>
                        <option value="video" @selected(($filters['type'] ?? '') === 'video')>Video</option>
                        <option value="hero" @selected(($filters['type'] ?? '') === 'hero')>Hero image</option>
                        <option value="post" @selected(($filters['type'] ?? '') === 'post')>News &amp; Event</option>
                    </select>
                </label>
                <label class="user-filter-field">
                    <span>Status</span>
                    <select name="status">
                        <option value="">All statuses</option>
                        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                        <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Approved</option>
                        <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Rejected</option>
                    </select>
                </label>
                <div class="user-filter-actions">
                    <button class="user-filter-apply" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i> Apply
                    </button>
                    @if (collect($filters)->contains(fn ($value) => filled($value)))
                        <a class="user-filter-clear" href="{{ route('admin.requests.index') }}">Clear</a>
                    @endif
                </div>
            </form>

            <p class="user-filter-result" role="status">
                @if ($requests->total() === 0)
                    Showing 0 requests
                @else
                    Showing {{ $requests->firstItem() }}–{{ $requests->lastItem() }} of {{ number_format($requests->total()) }} matching requests
                @endif
            </p>

            <div class="user-management-table-wrap">
                <table class="user-management-table">
                    <thead>
                        <tr>
                            <th scope="col">Request</th>
                            <th scope="col">Type</th>
                            <th scope="col">Submitted by</th>
                            <th scope="col">Status</th>
                            <th scope="col">Submitted</th>
                            <th scope="col">Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $userRequest)
                            <tr>
                                <td><strong>{{ $userRequest->title }}</strong></td>
                                <td>{{ $userRequest->type_label }}</td>
                                <td>
                                    <strong>{{ $userRequest->user_name }}</strong>
                                    <small>{{ $userRequest->user_email }}</small>
                                </td>
                                <td>
                                    <span class="user-status-badge {{ $userRequest->status }}">
                                        {{ ucfirst($userRequest->status) }}
                                    </span>
                                </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($userRequest->created_at)->format('M d, Y') }}</td>
                                <td>
                                    <a class="user-action-button promote" href="{{ $managementRoutes[$userRequest->request_type] }}">
                                        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> Open manager
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="user-management-empty" colspan="6">
                                    No user submissions match these filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($requests->hasPages())
                <div class="user-management-pagination">{{ $requests->links() }}</div>
            @endif
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
