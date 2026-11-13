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
            <a class="stat-card" href="{{ url('/admin/hero/manage') }}">
                <span class="stat-icon blue"><i class="fa-regular fa-image" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $heroImageCount }}</strong><small>Hero Images</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('admin.careers.index') }}">
                <span class="stat-icon green"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $careerCount }}</strong><small>Career Opportunities</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ url('/news-events') }}">
                <span class="stat-icon amber"><i class="fa-regular fa-newspaper" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>9</strong><small>News &amp; Announcements</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ url('/news-events') }}">
                <span class="stat-icon slate"><i class="fa-regular fa-calendar-days" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>6</strong><small>Event Calendar</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ url('/gallery/photo') }}">
                <span class="stat-icon amber"><i class="fa-regular fa-images" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>9</strong><small>Photos</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ url('/gallery/video') }}">
                <span class="stat-icon rose"><i class="fa-solid fa-circle-play" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>3</strong><small>Videos</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('admin.testimonials.index') }}">
                <span class="stat-icon blue"><i class="fa-regular fa-comment-dots" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $testimonialCount }}</strong><small>Testimonials</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ url('/campus-life') }}">
                <span class="stat-icon cyan"><i class="fa-solid fa-people-group" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>6</strong><small>Student Life</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ url('/admissions') }}">
                <span class="stat-icon blue"><i class="fa-solid fa-user-group" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>6</strong><small>Parents</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ route('admin.faqs.index') }}">
                <span class="stat-icon cyan"><i class="fa-regular fa-circle-question" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>{{ $faqCount }}</strong><small>FAQs</small></span>
                <i class="fa-solid fa-arrow-up-right-from-square stat-link" aria-hidden="true"></i>
            </a>
            <a class="stat-card" href="{{ url('/news-events/all-posts') }}">
                <span class="stat-icon green"><i class="fa-solid fa-file-lines" aria-hidden="true"></i></span>
                <span class="stat-copy"><strong>72</strong><small>Total Posts</small></span>
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
                                    @php($activityTime = $activity->created_at->setTimezone($activity->timezone))
                                    <tr>
                                        <td>
                                            <strong>{{ $activityTime->format('h:i A') }}</strong>
                                            <p>{{ $activityTime->format('M d, Y') }} · {{ $activity->timezone }}</p>
                                        </td>
                                        <td>{{ $activity->device }}</td>
                                        <td>{{ $activity->user_name }}</td>
                                        <td>{{ $activity->action }}</td>
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

                <section class="panel performance-panel">
                    <div class="panel-heading">
                        <div><h2>Department Performance</h2><p class="system_activity">Content activity by department</p></div>
                        <label class="period-select">This month <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></label>
                    </div>
                    <div class="performance-grid">
                        <div class="performance-item"><div><span>Science Department</span><strong>82%</strong></div><div class="progress-track"><span class="progress-green" style="width: 82%"></span></div></div>
                        <div class="performance-item"><div><span>Mathematics</span><strong>95%</strong></div><div class="progress-track"><span class="progress-blue" style="width: 95%"></span></div></div>
                        <div class="performance-item"><div><span>Language Arts</span><strong>68%</strong></div><div class="progress-track"><span class="progress-cyan" style="width: 68%"></span></div></div>
                        <div class="performance-item"><div><span>Social Studies</span><strong>76%</strong></div><div class="progress-track"><span class="progress-amber" style="width: 76%"></span></div></div>
                    </div>
                </section>
            </div>

            <aside class="dashboard-side-column">
                <section class="panel quick-actions-panel">
                    <div class="panel-heading"><div><h2>Quick Actions</h2><p class="system_activity">Jump to a school section</p></div></div>
                    <div class="quick-actions">
                        <a href="{{ route('admin.hero.manage') }}"><i class="fa-regular fa-images" aria-hidden="true"></i> Manage Hero Images</a>
                        <a href="{{ url('/news-events') }}"><i class="fa-regular fa-image" aria-hidden="true"></i> News Images</a>
                        <a href="{{ route('admin.careers.index') }}"><i class="fa-solid fa-briefcase" aria-hidden="true"></i> Career Opportunities</a>
                        <a href="{{ url('/news-events/all-posts') }}"><i class="fa-regular fa-newspaper" aria-hidden="true"></i> News &amp; Announcements</a>
                        <a href="{{ url('/gallery/photo') }}"><i class="fa-regular fa-images" aria-hidden="true"></i> Photo Gallery</a>
                        <a href="{{ url('/gallery/video') }}"><i class="fa-solid fa-circle-play" aria-hidden="true"></i> Video Gallery</a>
                        <a href="{{ route('admin.testimonials.index') }}"><i class="fa-regular fa-comment-dots" aria-hidden="true"></i> Testimonials</a>
                        <a href="{{ url('/campus-life') }}"><i class="fa-solid fa-people-group" aria-hidden="true"></i> Student Life</a>
                        <a href="{{ url('/admissions') }}"><i class="fa-solid fa-user-group" aria-hidden="true"></i> Parents</a>
                        <a href="{{ route('admin.faqs.index') }}"><i class="fa-regular fa-circle-question" aria-hidden="true"></i> FAQs</a>
                        <a href="{{ route('admin.users.index') }}"><i class="fa-solid fa-user-shield" aria-hidden="true"></i> User Accounts</a>
                        <a href="{{ route('admin.messages.index') }}"><i class="fa-regular fa-envelope" aria-hidden="true"></i> Contact Messages</a>
                    </div>
                </section>

                <section class="panel alerts-panel">
                    <div class="panel-heading"><div><h2>System Alerts</h2><p class="system_activity">Items that may need attention</p></div></div>
                    <div class="alert alert-danger"><strong><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Critical</strong><span>Server backup failed · 2 hours ago</span></div>
                    <div class="alert alert-warning"><strong><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> Warning</strong><span>5 teachers haven't submitted grades.</span></div>
                    <div class="alert alert-info"><strong><i class="fa-solid fa-circle-info" aria-hidden="true"></i> Info</strong><span>System maintenance scheduled tonight.</span></div>
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
