<div class="notification-menu" data-notification-menu>
    <button
        class="notification-menu-toggle"
        type="button"
        aria-label="Notifications, {{ $notificationCount }} pending"
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
        @endif
    </div>
</div>
