<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your FEC International School account activity history">
    <title>Account Activity | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-activity-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account-notifications.css') }}">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body class="admin-dashboard account-dashboard">
    @include('partials.header')

    <main class="dashboard-container">
        <header class="dashboard-heading">
            <div>
                <p class="eyebrow">FEC INTERNATIONAL SCHOOL</p>
                <h1>Account Activity</h1>
                <p class="dashboard-description">Your complete account activity history.</p>
                <p class="signed-in">Welcome, <strong>{{ $user->name }}</strong></p>
            </div>
            <a class="activity-back-link" href="{{ route('account.dashboard') }}">Back to dashboard</a>
        </header>

        @if ($filterErrors->any())
            <div class="activity-filter-error" role="alert">
                <strong>Some filter values are invalid.</strong>
                <ul>
                    @foreach ($filterErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="panel activity-panel" aria-labelledby="account-activity-title">
            <div class="panel-heading">
                <div>
                    <h2 id="account-activity-title">My Account Activity</h2>
                    <p class="system_activity">{{ $hasFilters ? 'Matching activity for your account' : 'All recorded activity for your account' }}</p>
                </div>
                <span class="submission-update-count">{{ number_format($activities->total()) }}</span>
            </div>
            <form class="activity-filter-form" action="{{ route('account.activities.index') }}" method="GET">
                <label class="activity-filter-field activity-filter-search">
                    <span>Search</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="Action or details">
                </label>
                <label class="activity-filter-field">
                    <span>Action</span>
                    <select name="action">
                        <option value="">All actions</option>
                        @foreach ($actions as $action)
                            <option value="{{ $action }}" @selected(($filters['action'] ?? '') === $action)>{{ $action }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="activity-filter-field">
                    <span>Status</span>
                    <select name="status">
                        <option value="">All statuses</option>
                        @foreach (['Success', 'Failed'] as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="activity-filter-field">
                    <span>Device</span>
                    <select name="device">
                        <option value="">All devices</option>
                        @foreach (['Desktop', 'Mobile', 'Tablet', 'Unknown'] as $device)
                            <option value="{{ $device }}" @selected(($filters['device'] ?? '') === $device)>{{ $device }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="activity-filter-actions">
                    <button class="activity-filter-apply" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i> Apply filters
                    </button>
                    @if ($hasFilters)
                        <a class="activity-filter-clear" href="{{ route('account.activities.index') }}">Clear</a>
                    @endif
                </div>
            </form>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th scope="col">Time</th><th scope="col">Device</th><th scope="col">Action</th><th scope="col">Details</th><th scope="col">Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $activity)
                            @php($activityTime = $activity->created_at->copy()->setTimezone($activity->timezone))
                            <tr>
                                <td>
                                    <strong>{{ $activityTime->format('h:i A') }}</strong>
                                    <p>{{ $activityTime->format('M d, Y') }} · {{ $activity->timezone }}</p>
                                </td>
                                <td>{{ $activity->device }}</td>
                                <td>@include('admin.partials.activity-action')</td>
                                <td>{{ $activity->details }}</td>
                                <td><span class="status-badge {{ $activity->status === 'Success' ? 'success' : 'danger' }}">{{ $activity->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No activity has been recorded for your account yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($activities->hasPages())
                <nav class="activity-pagination" aria-label="Activity pages">
                    <p>
                        Showing {{ $activities->firstItem() }}–{{ $activities->lastItem() }}
                        of {{ $activities->total() }} activities
                    </p>
                    <div class="activity-pagination-actions">
                        @if ($activities->previousPageUrl())
                            <a href="{{ $activities->previousPageUrl() }}" rel="prev">
                                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Previous
                            </a>
                        @else
                            <span aria-disabled="true">Previous</span>
                        @endif
                        <span class="activity-pagination-page">Page {{ $activities->currentPage() }} of {{ $activities->lastPage() }}</span>
                        @if ($activities->nextPageUrl())
                            <a href="{{ $activities->nextPageUrl() }}" rel="next">
                                Next <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        @else
                            <span aria-disabled="true">Next</span>
                        @endif
                    </div>
                </nav>
            @endif
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
