<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FEC International School administration dashboard">
    <title>Admin Dashboard | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body class="admin-dashboard admin-dashboard-home">
    @include('partials.header')

    <main class="dashboard-container">
        <div class="dashboard-heading">
            <div>
                <p class="eyebrow">FEC INTERNATIONAL SCHOOL</p>
                <h1>Admin Dashboard</h1>
                <p class="dashboard-description">System administration overview and school content management.</p>
                <p class="signed-in">Logged in as <strong>Admin</strong> <span aria-hidden="true">·</span> administrator</p>
            </div>
            <div class="dashboard-meta">
                <span>Last updated</span>
                <strong>{{ now()->setTimezone(session('device_timezone', 'UTC'))->format('M d, Y · h:i A') }}</strong>
                <span class="system-status"><i aria-hidden="true"></i> System operational</span>
            </div>
        </div>

        <section class="stats-grid" aria-label="School content overview">
            <a class="stat-card has-approval-counts" href="{{ url('/admin/hero/manage') }}">
                <span class="stat-icon blue"><i class="fa-regular fa-image" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $heroImageCount }}</strong><small>Hero Images</small></span>
                <span class="stat-approval-counts">
                    <span class="stat-pending-count"><strong>Pending</strong> {{ number_format($pendingHeroApprovalCount) }}</span>
                    <span class="stat-approved-count"><strong>Approved</strong> {{ number_format($approvedHeroCount) }}</span>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('admin.careers.index') }}">
                <span class="stat-icon green"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $careerCount }}</strong><small>Career Opportunities</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card has-approval-counts" href="{{ route('admin.posts.index') }}">
                <span class="stat-icon amber"><i class="fa-regular fa-newspaper" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $postCount }}</strong><small>News, Announcements &amp; Events</small></span>
                <span class="stat-approval-counts">
                    <span class="stat-pending-count"><strong>Pending</strong> {{ number_format($pendingPostApprovalCount) }}</span>
                    <span class="stat-approved-count"><strong>Approved</strong> {{ number_format($approvedPostCount) }}</span>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('admin.events.calendar') }}">
                <span class="stat-icon slate"><i class="fa-regular fa-calendar-days" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $eventPostCount }}</strong><small>Event Calendar</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card has-approval-counts" href="{{ route('admin.gallery.photos.index') }}">
                <span class="stat-icon amber"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $galleryPhotoCount }}</strong><small>Photos</small></span>
                <span class="stat-approval-counts">
                    <span class="stat-pending-count"><strong>Pending</strong> {{ number_format($pendingPhotoApprovalCount) }}</span>
                    <span class="stat-approved-count"><strong>Approved</strong> {{ number_format($approvedPhotoCount) }}</span>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card has-approval-counts" href="{{ route('admin.gallery.videos.index') }}">
                <span class="stat-icon rose"><i class="fa-solid fa-circle-play" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $galleryVideoCount }}</strong><small>Videos</small></span>
                <span class="stat-approval-counts">
                    <span class="stat-pending-count"><strong>Pending</strong> {{ number_format($pendingVideoApprovalCount) }}</span>
                    <span class="stat-approved-count"><strong>Approved</strong> {{ number_format($approvedVideoCount) }}</span>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ url('/campus-life') }}">
                <span class="stat-icon cyan"><i class="fa-solid fa-people-group" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>6</strong><small>Campus Life</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('admin.testimonials.index') }}">
                <span class="stat-icon blue"><i class="fa-regular fa-comment-dots" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $testimonialCount }}</strong><small>Testimonials</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>              
            <a class="stat-card" href="{{ route('admin.faqs.index') }}">
                <span class="stat-icon cyan"><i class="fa-regular fa-circle-question" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $faqCount }}</strong><small>FAQs</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>            
            <a class="stat-card" href="{{ route('admin.messages.index') }}">
                <span class="stat-icon amber"><i class="fa-regular fa-envelope" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $messageCount }}</strong><small>Messages</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('admin.users.index') }}">
                <span class="stat-icon slate"><i class="fa-solid fa-user-shield" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $userCount }}</strong><small>User Accounts</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('admin.users.index') }}">
                <span class="stat-icon rose"><i class="fa-solid fa-user-clock" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $pendingCount }}</strong><small>Pending Registrations</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <!--
            <a class="stat-card" href="{{ url('/news-events/all-posts') }}">
                <span class="stat-icon green"><i class="fa-solid fa-file-lines" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $totalContentCount }}</strong><small>Total Posts</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            -->
        </section>

        <div class="dashboard-columns">
            <div class="dashboard-main-column">
                <section class="panel activity-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>System Activities</h2>
                            <p class="system_activity">Recent account and content activity</p>
                        </div>
                        <a class="text-link" href="{{ route('admin.activities.index') }}">View all <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="table-wrap">
                        <table id="system-activities">
                            <thead>
                                <tr><th>Time</th><th>Device</th><th>User</th><th>Action</th><th>Details</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $activity)
                                    <tr>
                                        <td>
                                            <strong>{{ $activity->created_at->setTimezone($activity->timezone)->format('h:i A') }}</strong>
                                            <p>{{ $activity->created_at->setTimezone($activity->timezone)->format('M d, Y') }} · {{ $activity->timezone }}</p>
                                        </td>
                                        <td>{{ $activity->device }}</td>
                                        <td>{{ $activity->user_name }}</td>
                                        <td>@include('admin.partials.activity-action')</td>
                                        <td>{{ $activity->details }}</td>
                                        <td><span class="status-badge {{ $activity->status === 'Success' ? 'success' : 'danger' }}">{{ $activity->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6">No system activities have been recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="panel user-activity-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>All User Activity</h2>
                            <p class="system_activity">Daily activity by category across all users</p>
                        </div>
                        <span class="user-activity-chart-period">Last 30 days</span>
                    </div>
                    <div class="user-activity-chart-legend" aria-label="Activity totals by category">
                        @foreach ($activityChartSeries as $series)
                            <span>
                                <i class="user-activity-chart-key {{ $series['key'] }}" aria-hidden="true"></i>
                                {{ $series['label'] }} <strong>{{ number_format($series['total']) }}</strong>
                            </span>
                        @endforeach
                    </div>
                    <div class="user-activity-chart-scroll">
                        <svg class="user-activity-chart" viewBox="0 0 720 260" role="img" aria-labelledby="activity-chart-title activity-chart-description">
                            <title id="activity-chart-title">User activity by category over the last 30 days</title>
                            <desc id="activity-chart-description">Daily counts of photo gallery, video gallery, content, account, and other activities across all users.</desc>
                            @foreach ($activityChartGridLines as $gridLine)
                                <line class="user-activity-chart-grid-line" x1="48" y1="{{ $gridLine['y'] }}" x2="702" y2="{{ $gridLine['y'] }}"></line>
                                <text class="user-activity-chart-axis-label" x="40" y="{{ $gridLine['y'] + 4 }}" text-anchor="end">{{ $gridLine['value'] }}</text>
                            @endforeach
                            @foreach ($activityChartSeries as $series)
                                <polyline class="user-activity-chart-line {{ $series['key'] }}" points="{{ $series['points'] }}"></polyline>
                            @endforeach
                            @foreach ($activityChartXLabels as $label)
                                <text class="user-activity-chart-axis-label" x="{{ $label['x'] }}" y="246" text-anchor="middle">{{ $label['label'] }}</text>
                            @endforeach
                        </svg>
                    </div>
                </section>

                <section class="panel other-issues-panel" aria-labelledby="admin-other-issues-title">
                    <div class="panel-heading">
                        <div>
                            <h2 id="admin-other-issues-title">Other Issues</h2>
                            <p class="system_activity">Failed activity records from the last 30 days</p>
                        </div>
                        <span class="other-issues-count">{{ number_format($failedActivityCount) }}</span>
                    </div>
                    <div class="other-issues-list">
                        @forelse ($failedActivities as $activity)
                            <a class="other-issue-item" href="{{ route('admin.activities.index', ['status' => 'Failed']) }}">
                                <span class="other-issue-copy">
                                    <strong>{{ $activity->action }} · {{ $activity->user_name }}</strong>
                                    <small>
                                        {{ $activity->details }} ·
                                        {{ $activity->created_at->copy()->setTimezone($activity->timezone)->format('M d, Y · h:i A') }}
                                    </small>
                                </span>
                                <span class="status-badge danger">Failed</span>
                            </a>
                        @empty
                            <p class="other-issues-empty">No failed activities were recorded in the last 30 days.</p>
                        @endforelse
                    </div>
                    <a class="text-link other-issues-view-all" href="{{ route('admin.activities.index', ['status' => 'Failed']) }}">
                        View failed activity records <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </section>
            </div>

            <aside class="dashboard-side-column">
                <section class="panel content-distribution-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>Admin Content Distribution</h2>
                            <p class="system_activity">All managed content by type</p>
                        </div>
                    </div>
                    <figure class="content-distribution-figure">
                        <div class="content-distribution-chart"
                            style="background: {{ $chartBackground }}"
                            role="img"
                            aria-label="Pie chart of {{ $totalContentCount }} admin-managed content records">
                            <div class="content-distribution-chart-center" aria-hidden="true">
                                <strong>{{ number_format($totalContentCount) }}</strong>
                                <span>Total</span>
                            </div>
                        </div>
                        <figcaption>
                            <ul class="content-distribution-legend">
                                @foreach ($contentDistribution as $item)
                                    <li>
                                        <span class="content-distribution-label">
                                            <span class="content-distribution-swatch" style="--content-color: {{ $item['color'] }}" aria-hidden="true"></span>
                                            {{ $item['label'] }}
                                        </span>
                                        <span class="content-distribution-value">
                                            <strong>{{ number_format($item['count']) }}</strong>
                                            <small>{{ $totalContentCount > 0 ? number_format($item['count'] / $totalContentCount * 100, 1) : '0.0' }}%</small>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </figcaption>
                    </figure>
                </section>

                <section class="panel alerts-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>Pending User Submissions</h2>
                            <p class="system_activity">Content waiting for administrator review</p>
                        </div>
                    </div>
                    @if ($pendingPhotoApprovalCount > 0)
                        <a class="alert alert-warning pending-approval-alert" href="{{ route('admin.gallery.photos.index') }}">
                            <strong><i class="fa-regular fa-images" aria-hidden="true"></i> {{ $pendingPhotoApprovalCount }} pending photo {{ $pendingPhotoApprovalCount === 1 ? 'submission' : 'submissions' }}</strong>
                            <span>Review user-submitted gallery photos</span>
                        </a>
                    @endif
                    @if ($pendingHeroApprovalCount > 0)
                        <a class="alert alert-warning pending-approval-alert" href="{{ route('admin.hero.manage') }}">
                            <strong><i class="fa-regular fa-images" aria-hidden="true"></i> {{ $pendingHeroApprovalCount }} pending hero image {{ $pendingHeroApprovalCount === 1 ? 'submission' : 'submissions' }}</strong>
                            <span>Review user-submitted homepage hero images</span>
                        </a>
                    @endif
                    @if ($pendingVideoApprovalCount > 0)
                        <a class="alert alert-warning pending-approval-alert" href="{{ route('admin.gallery.videos.index') }}">
                            <strong><i class="fa-solid fa-circle-play" aria-hidden="true"></i> {{ $pendingVideoApprovalCount }} pending video {{ $pendingVideoApprovalCount === 1 ? 'submission' : 'submissions' }}</strong>
                            <span>Review user-submitted gallery videos</span>
                        </a>
                    @endif
                    @if ($pendingPostApprovalCount > 0)
                        <a class="alert alert-warning pending-approval-alert" href="{{ route('admin.posts.index') }}">
                            <strong><i class="fa-regular fa-newspaper" aria-hidden="true"></i> {{ $pendingPostApprovalCount }} pending News/Event {{ $pendingPostApprovalCount === 1 ? 'post' : 'posts' }}</strong>
                            <span>Review user-submitted News and Events</span>
                        </a>
                    @endif
                    @if ($pendingPhotoApprovalCount + $pendingVideoApprovalCount + $pendingPostApprovalCount + $pendingHeroApprovalCount === 0)
                        <div class="alert alert-info"><strong><i class="fa-solid fa-circle-check" aria-hidden="true"></i> All caught up</strong><span>No user content is waiting for approval.</span></div>
                    @endif
                </section>

                <section class="panel events-panel">
                    <div class="panel-heading"><div><h2>Upcoming Events</h2><p class="system_activity">What's coming up at school</p></div></div>
                    <a class="event-row" href="{{ url('/news-events') }}"><span><strong>Board Meeting</strong><small>Today · 2:00 PM</small></span><i class="fa-solid fa-users event-red" aria-hidden="true"></i></a>
                    <a class="event-row" href="{{ url('/news-events') }}"><span><strong>Parent Conference</strong><small>Tomorrow · 10:00 AM</small></span><i class="fa-solid fa-user-group event-blue" aria-hidden="true"></i></a>
                    <a class="event-row" href="{{ url('/news-events') }}"><span><strong>Staff Meeting</strong><small>Friday · 3:00 PM</small></span><i class="fa-solid fa-chalkboard-user event-green" aria-hidden="true"></i></a>
                </section>

                <section class="panel storage-panel">
                    <div class="panel-heading"><div><h2>Storage Usage</h2><p class="system_activity">Media library capacity</p></div></div>
                    <div class="storage-item"><div><span>Database</span><strong>45%</strong></div><div class="progress-track"><span class="progress-blue" style="width: 45%"></span></div></div>
                    <div class="storage-item"><div><span>Documents</span><strong>72%</strong></div><div class="progress-track"><span class="progress-amber" style="width: 72%"></span></div></div>
                    <div class="storage-item"><div><span>Media Files</span><strong>30%</strong></div><div class="progress-track"><span class="progress-green" style="width: 30%"></span></div></div>
                    <p class="storage-caption">7.2 GB of 20 GB used</p>
                </section>
            </aside>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>
