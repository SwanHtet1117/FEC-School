<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your FEC International School account dashboard">
    <title>My Dashboard | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account-notifications.css') }}">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body class="admin-dashboard account-dashboard">
    @include('partials.header')

    <main class="dashboard-container">
        @php
            $dashboardHour = (int) now()->setTimezone(session('device_timezone', 'UTC'))->format('G');
            $dashboardGreeting = $dashboardHour < 12 ? 'Good morning' : ($dashboardHour < 18 ? 'Good afternoon' : 'Good evening');
        @endphp
        <header class="dashboard-heading">
            <div>
                <p class="eyebrow">FEC INTERNATIONAL SCHOOL</p>
                <h1>{{ $dashboardGreeting }}, <span>{{ $user->name }}</span></h1>
                <p class="dashboard-description">Your account overview, submissions, and recent activity.</p>
                <p class="signed-in">Welcome back, <strong>{{ $user->name }}</strong></p>
            </div>
            <div class="dashboard-meta">
                <span>Member since</span>
                <strong>{{ $user->created_at->setTimezone(session('device_timezone', 'UTC'))->format('M d, Y') }}</strong>
                <span class="system-status"><i aria-hidden="true"></i> {{ $user->is_approved ? 'Account active' : 'Awaiting approval' }}</span>
            </div>
        </header>

        <section class="stats-grid" aria-label="Your account overview">           
            <a class="stat-card" href="{{ route('account.content.photos') }}">
                <span class="stat-icon blue"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>Manage photos</strong><small>Submit photos for review</small></span>
                <span class="stat-status-counts">
                    <span><strong>{{ number_format($photoPendingCount) }}</strong><small>Pending</small></span>
                    <span><strong>{{ number_format($photoApprovedCount) }}</strong><small>Approved</small></span>
                    <span><strong>{{ number_format($photoCount) }}</strong><small>Total</small></span>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('account.content.videos') }}">
                <span class="stat-icon rose"><i class="fa-solid fa-circle-play" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>Manage videos</strong><small>Submit videos for review</small></span>
                <span class="stat-status-counts">
                    <span><strong>{{ number_format($videoPendingCount) }}</strong><small>Pending</small></span>
                    <span><strong>{{ number_format($videoApprovedCount) }}</strong><small>Approved</small></span>
                    <span><strong>{{ number_format($videoCount) }}</strong><small>Total</small></span>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('account.content.posts') }}">
                <span class="stat-icon green"><i class="fa-regular fa-newspaper" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>Manage News&amp;Events</strong><small>Submit posts for review</small></span>
                <span class="stat-status-counts">
                    <span><strong>{{ number_format($postPendingCount) }}</strong><small>Pending</small></span>
                    <span><strong>{{ number_format($postApprovedCount) }}</strong><small>Approved</small></span>
                    <span><strong>{{ number_format($postCount) }}</strong><small>Total</small></span>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ $user->is_admin ? route('admin.hero.manage') : route('account.content.hero') }}">
                <span class="stat-icon cyan"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <span class="stat-copy">
                    <strong>Manage Hero Images</strong>
                    <small>{{ $user->is_admin ? 'Manage the live homepage slideshow' : 'Submit images for admin review' }}</small>
                </span>
                <span class="stat-status-counts">
                    <span><strong>{{ number_format($heroImagePendingCount) }}</strong><small>Pending</small></span>
                    <span><strong>{{ number_format($heroImageApprovedCount) }}</strong><small>Approved</small></span>
                    <span><strong>{{ number_format($heroImageCount) }}</strong><small>Total</small></span>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
        </section>

        <div class="dashboard-columns">
            <div class="dashboard-main-column">
                <section class="panel account-submission-notifications" aria-labelledby="submission-updates-title">
                    <div class="panel-heading">
                        <div>
                            <h2 id="submission-updates-title">Submission updates</h2>
                            <p class="system_activity">Review status for your recent submissions</p>
                        </div>
                        <span class="submission-update-count">{{ $submissionUpdates->count() }}</span>
                    </div>
                    <div class="submission-update-list">
                        @forelse ($submissionUpdates as $update)
                            @php
                                $updateStatus = $update['status'] ?? ($update['approved'] ? 'Approved' : 'Awaiting review');
                                $updateStatusClass = $update['status_class'] ?? ($update['approved'] ? 'success' : 'pending');
                            @endphp
                            <a class="submission-update-item" href="{{ $update['url'] }}">
                                <span class="submission-update-icon" aria-hidden="true"><i class="{{ $update['icon'] }}"></i></span>
                                <span class="submission-update-copy">
                                    <strong>{{ $update['title'] }}</strong>
                                    <small>{{ $update['type'] }} · Updated {{ $update['updated_at']->format('M d, Y') }}</small>
                                </span>
                                <span class="status-badge {{ $updateStatusClass }}">
                                    {{ $updateStatus }}
                                </span>
                            </a>
                        @empty
                            <p class="submission-update-empty">Updates about your submitted photos, videos, posts, and hero images will appear here.</p>
                        @endforelse
                    </div>
                </section>

                <section class="panel activity-panel" id="my-activity" aria-labelledby="my-activity-title">
                    <div class="panel-heading">
                        <div>
                            <h2 id="my-activity-title">My Account Activity</h2>
                            <p class="system_activity">Today's activity for your account</p>
                        </div>
                        <a class="activity-view-all" href="{{ route('account.activities.index') }}">View all</a>
                    </div>
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
                </section>
            </div>

            <aside class="dashboard-side-column">
                <section class="panel account-activity-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>Account Activity Snapshot</h2>
                            <p class="system_activity">Activity distribution for your account</p>
                        </div>
                    </div>
                    <figure class="account-activity-figure">
                        <div class="account-activity-chart"
                            style="background: {{ $accountActivityChartBackground }}"
                            role="img"
                            aria-label="Pie chart of {{ $totalAccountActivityCount }} account activities">
                            <div class="account-activity-chart-center" aria-hidden="true">
                                <strong>{{ number_format($totalAccountActivityCount) }}</strong>
                                <span>Total</span>
                            </div>
                        </div>
                        <figcaption>
                            <ul class="account-activity-legend">
                                @foreach ($accountActivityDistribution as $item)
                                    <li>
                                        <span class="account-activity-card-label">
                                            <span class="account-activity-swatch" style="--activity-color: {{ $item['color'] }}" aria-hidden="true"></span>
                                            {{ $item['label'] }}
                                        </span>
                                        <strong class="account-activity-card-count">{{ number_format($item['count']) }}</strong>
                                        <small class="account-activity-card-percent">{{ $totalAccountActivityCount > 0 ? number_format($item['count'] / $totalAccountActivityCount * 100, 1) : '0.0' }}%</small>
                                    </li>
                                @endforeach
                            </ul>
                        </figcaption>
                    </figure>
                </section>

                <section class="panel">
                    <div class="panel-heading">
                        <div>
                            <h2>Account details</h2>
                            <p class="system_activity">Your registered information</p>
                        </div>
                    </div>
                    <p><strong>Name</strong><br>{{ $user->name }}</p>
                    <p><strong>Email</strong><br>{{ $user->email }}</p>
                    <p><strong>Phone</strong><br>{{ $user->phone ?: 'Not provided' }}</p>
                </section>

                <section class="panel other-issues-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>Other Issues</h2>
                            <p class="system_activity">Items that need your attention</p>
                        </div>
                        <span class="other-issues-count">{{ number_format($otherIssues->count()) }}</span>
                    </div>
                    <div class="other-issues-list">
                        @forelse ($otherIssues as $issue)
                            <a class="other-issue-item" href="{{ $issue['url'] }}">
                                <span class="other-issue-copy">
                                    <strong>{{ $issue['label'] }}</strong>
                                    <small>{{ $issue['detail'] }}</small>
                                </span>
                                <strong class="other-issue-count">{{ number_format($issue['count']) }}</strong>
                            </a>
                        @empty
                            <p class="other-issues-empty">No other issues need your attention.</p>
                        @endforelse
                    </div>
                </section>
            </aside>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>
