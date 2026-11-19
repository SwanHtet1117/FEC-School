<div class="account-menu" data-account-menu>
    <button class="account-menu-toggle" type="button" aria-haspopup="true" aria-expanded="false">
        <i class="fa-regular fa-circle-user" aria-hidden="true"></i>
        <span>{{ auth()->user()->name }}</span>
        <i class="fa-solid fa-chevron-down account-menu-chevron" aria-hidden="true"></i>
    </button>
    <div class="account-menu-dropdown">
        @if (auth()->user()->is_admin)
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-gauge-high" aria-hidden="true"></i> Dashboard
            </a>
            <a href="{{ route('admin.requests.index') }}">
                <i class="fa-solid fa-user-clock" aria-hidden="true"></i> View Requests
            </a>
        @else
            <a href="{{ route('account.dashboard') }}">
                <i class="fa-solid fa-gauge-high" aria-hidden="true"></i> Dashboard
            </a>
        @endif
        <a href="{{ route('account.profile.edit') }}">
            <i class="fa-regular fa-user" aria-hidden="true"></i> Profile
        </a>
        <a href="{{ route('account.settings.edit') }}">
            <i class="fa-solid fa-gear" aria-hidden="true"></i> Settings
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">
                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Logout
            </button>
        </form>
    </div>
</div>
