<div class="notification-menu" data-notification-menu>
    <button
        class="notification-menu-toggle"
        type="button"
        aria-label="{{ $notificationLabel }}"
        aria-haspopup="true"
        aria-expanded="false"
    >
        <i class="fa-regular fa-bell" aria-hidden="true"></i>
        @if ($notificationCount > 0)
            <span class="notification-menu-count">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
        @endif
    </button>
    <div class="notification-menu-dropdown" aria-label="Notifications">
        <div class="notification-menu-heading">Notifications</div>
        @if ($notificationCount === 0)
            <p class="notification-menu-empty">You're all caught up.</p>
        @else
            @auth
                @if (auth()->user()->is_admin)
                    @if ($pendingCount > 0)
                        <a class="notification-menu-item" href="{{ route('admin.users.index') }}">
                            <i class="fa-solid fa-user-clock" aria-hidden="true"></i>
                            <span>Pending registrations</span>
                            <strong>{{ number_format($pendingCount) }}</strong>
                        </a>
                    @endif
                    @if ($unreadMessageCount > 0)
                        <a class="notification-menu-item" href="{{ route('admin.messages.index') }}">
                            <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                            <span>Unread contact messages</span>
                            <strong>{{ number_format($unreadMessageCount) }}</strong>
                        </a>
                    @endif
                    @if ($pendingSubmissionCounts['photos'] > 0)
                        <a class="notification-menu-item" href="{{ route('admin.gallery.photos.index') }}">
                            <i class="fa-regular fa-images" aria-hidden="true"></i>
                            <span>Pending photo submissions</span>
                            <strong>{{ number_format($pendingSubmissionCounts['photos']) }}</strong>
                        </a>
                    @endif
                    @if ($pendingSubmissionCounts['videos'] > 0)
                        <a class="notification-menu-item" href="{{ route('admin.gallery.videos.index') }}">
                            <i class="fa-solid fa-circle-play" aria-hidden="true"></i>
                            <span>Pending video submissions</span>
                            <strong>{{ number_format($pendingSubmissionCounts['videos']) }}</strong>
                        </a>
                    @endif
                    @if ($pendingSubmissionCounts['posts'] > 0)
                        <a class="notification-menu-item" href="{{ route('admin.posts.index') }}">
                            <i class="fa-regular fa-newspaper" aria-hidden="true"></i>
                            <span>Pending News/Event posts</span>
                            <strong>{{ number_format($pendingSubmissionCounts['posts']) }}</strong>
                        </a>
                    @endif
                    @if ($pendingSubmissionCounts['hero_images'] > 0)
                        <a class="notification-menu-item" href="{{ route('admin.hero.manage') }}">
                            <i class="fa-regular fa-image" aria-hidden="true"></i>
                            <span>Pending hero image submissions</span>
                            <strong>{{ number_format($pendingSubmissionCounts['hero_images']) }}</strong>
                        </a>
                    @endif
                @else
                    @foreach ($accountNotifications as $notification)
                        <a class="notification-menu-item account-notification-item" href="{{ $notification['url'] }}">
                            <i class="{{ $notification['icon'] }}" aria-hidden="true"></i>
                            <span>
                                <strong>{{ $notification['title'] }}</strong>
                                <small>{{ $notification['details'] }}</small>
                                <small>{{ $notification['date'] }}</small>
                            </span>
                        </a>
                    @endforeach
                    <a class="notification-menu-all" href="{{ route('account.activities.index') }}">View all activity updates</a>
                @endif
            @endif
        @endif
    </div>
</div>
