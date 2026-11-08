<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Review and manage FEC International School user accounts">
    <title>User Management | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-user-management.css') }}">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container user-management">
        <header class="user-management-heading">
            <div>
                <a class="user-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <h1>User Management</h1>
                <p>Review new registrations, approve accounts, or remove users.</p>
            </div>
            <span class="user-management-count">{{ $userCount }} {{ $userCount === 1 ? 'Account' : 'Accounts' }}</span>
        </header>

        @if (session('status'))
            <p class="user-management-notice success" role="status">{{ session('status') }}</p>
        @endif
        @if ($errors->has('user'))
            <p class="user-management-notice error" role="alert">{{ $errors->first('user') }}</p>
        @endif

        <section class="user-management-stats" aria-label="Account status totals">
            <div><span>Total accounts</span><strong>{{ $userCount }}</strong></div>
            <div><span>Approved</span><strong>{{ $approvedCount }}</strong></div>
            <div><span>Awaiting approval</span><strong>{{ $pendingCount }}</strong></div>
        </section>

        <section class="user-management-panel" aria-labelledby="user-list-title">
            <div class="user-management-panel-heading">
                <div>
                    <h2 id="user-list-title">Accounts</h2>
                    <p>Pending registrations appear first.</p>
                </div>
            </div>
            <div class="user-management-table-wrap">
                <table class="user-management-table">
                    <thead>
                        <tr>
                            <th scope="col">User</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col">Registered</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    <small>{{ $user->email }}</small>
                                </td>
                                <td>{{ $user->phone ?: '—' }}</td>
                                <td>{{ $user->is_admin ? 'Administrator' : 'User' }}</td>
                                <td>
                                    <span class="user-status-badge {{ $user->is_approved ? 'approved' : 'pending' }}">
                                        {{ $user->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="user-management-actions">
                                        @if (! $user->is_approved && ! $user->is_admin)
                                            <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                                @csrf
                                                <button class="user-action-button approve" type="submit">
                                                    <i class="fa-solid fa-check" aria-hidden="true"></i> Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if (auth()->id() === $user->id)
                                            <span class="user-current-account">Current Account</span>
                                        @elseif ($user->is_admin && $adminCount <= 1)
                                            <span class="user-current-account">Last administrator</span>
                                        @else
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user account? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="user-action-button delete" type="submit">
                                                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="user-management-empty" colspan="6">No user accounts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="user-management-pagination">{{ $users->links() }}</div>
            @endif
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
