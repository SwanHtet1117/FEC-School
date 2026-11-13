<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Review system activity at FEC International School">
    <title>System Activities | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-activity-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container activity-management">
        <header class="activity-management-heading">
            <div>
                <a class="activity-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <h1>System Activities</h1>
                <p>Review the complete record of account and content activity.</p>
            </div>
            <span class="activity-management-count">
                {{ $activities->total() }} {{ $hasFilters ? 'Matching Activities' : 'Recorded Activities' }}
            </span>
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

        <section class="panel activity-panel activity-management-panel" aria-labelledby="activity-list-title">
            <div class="panel-heading">
                <div>
                    <h2 id="activity-list-title">Activity Log</h2>
                    <p class="system_activity">Newest activities appear first. Times use each record's saved timezone.</p>
                </div>
            </div>

            <form class="activity-filter-form" action="{{ route('admin.activities.index') }}" method="GET">
                <label class="activity-filter-field activity-filter-search">
                    <span>Search</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="User, action, or details">
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
                        <a class="activity-filter-clear" href="{{ route('admin.activities.index') }}">Clear</a>
                    @endif
                </div>
            </form>

            <div class="activity-management-table-wrap">
                <table class="activity-management-table">
                    <thead>
                        <tr>
                            <th scope="col">Time</th>
                            <th scope="col">Device</th>
                            <th scope="col">User</th>
                            <th scope="col">Action</th>
                            <th scope="col">Details</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $activity)
                            @php($activityTime = $activity->created_at->copy()->setTimezone($activity->timezone))
                            <tr>
                                <td>
                                    <strong>{{ $activityTime->format('h:i A') }}</strong>
                                    <small>{{ $activityTime->format('M d, Y') }} · {{ $activity->timezone }}</small>
                                </td>
                                <td>{{ $activity->device }}</td>
                                <td>{{ $activity->user_name }}</td>
                                <td>{{ $activity->action }}</td>
                                <td>{{ $activity->details }}</td>
                                <td>
                                    <span class="status-badge {{ $activity->status === 'Success' ? 'success' : 'danger' }}">
                                        {{ $activity->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="activity-management-empty" colspan="6">
                                    @if ($filterErrors->any())
                                        Please correct the filters above to view matching activities.
                                    @elseif ($hasFilters)
                                        No activities match these filters.
                                    @else
                                        No system activities have been recorded yet.
                                    @endif
                                </td>
                            </tr>
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
