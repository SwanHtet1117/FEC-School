<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Review and manage messages sent through the FEC International School contact form">
    <title>Contact Messages | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-user-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-contact-messages.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container contact-message-management">
        <header class="user-management-heading">
            <div>
                <a class="user-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <h1>Contact Messages</h1>
                <p>Review and respond to inquiries submitted through the contact page.</p>
            </div>
            <span class="user-management-count">{{ $messageCount }} {{ $messageCount === 1 ? 'Message' : 'Messages' }}</span>
        </header>

        @if (session('status'))
            <p class="user-management-notice success" role="status">{{ session('status') }}</p>
        @endif
        @if ($filterErrors->any())
            <div class="user-management-notice warning" role="alert">
                <strong>Some filter values are invalid.</strong>
                <ul>
                    @foreach ($filterErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="user-management-stats" aria-label="Message status totals">
            <div><p>Total messages</p><strong>{{ $messageCount }}</strong></div>
            <div><p>Unread</p><strong>{{ $unreadCount }}</strong></div>
            <div><p>Read</p><strong>{{ $readCount }}</strong></div>
        </section>

        <section class="user-management-panel" aria-labelledby="message-list-title">
            <div class="user-management-panel-heading">
                <div>
                    <h2 id="message-list-title">Inbox</h2>
                    <p>Newest messages appear first.</p>
                </div>
            </div>
            <form class="contact-message-filter-form" action="{{ route('admin.messages.index') }}" method="GET">
                <label class="contact-message-filter-field contact-message-filter-search">
                    <span>Search messages</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="Sender, email, phone, subject, or message">
                </label>
                <label class="contact-message-filter-field">
                    <span>Subject</span>
                    <select name="subject">
                        <option value="">All subjects</option>
                        @foreach ($subjects as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['subject'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="contact-message-filter-field">
                    <span>Status</span>
                    <select name="status">
                        <option value="">All statuses</option>
                        <option value="unread" @selected(($filters['status'] ?? '') === 'unread')>Unread</option>
                        <option value="read" @selected(($filters['status'] ?? '') === 'read')>Read</option>
                    </select>
                </label>
                <div class="contact-message-filter-actions">
                    <button class="contact-message-filter-apply" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i> Apply filters
                    </button>
                    @if ($hasFilters)
                        <a class="contact-message-filter-clear" href="{{ route('admin.messages.index') }}">Clear</a>
                    @endif
                </div>
            </form>
            <p class="contact-message-filter-result" role="status">
                @if ($messages->total() === 0)
                    Showing 0 of {{ $messageCount }} messages
                @else
                    Showing {{ $messages->firstItem() }}–{{ $messages->lastItem() }} of {{ $messages->total() }} {{ $hasFilters ? 'matching' : 'total' }} messages
                @endif
            </p>
            <div class="user-management-table-wrap">
                <table class="user-management-table contact-message-table">
                    <thead>
                        <tr>
                            <th scope="col">Sender</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Message</th>
                            <th scope="col">Received</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($filterErrors->any())
                            <tr><td class="user-management-empty" colspan="6">Please correct the filters above to view messages.</td></tr>
                        @else
                        @forelse ($messages as $contactMessage)
                            <tr class="{{ $contactMessage->is_read ? '' : 'contact-message-unread' }}">
                                <td>
                                    <strong>{{ $contactMessage->name }}</strong>
                                    <a class="contact-message-email" href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a>
                                    @if ($contactMessage->phone)
                                        <small>{{ $contactMessage->phone }}</small>
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::headline($contactMessage->subject) }}</td>
                                <td class="contact-message-content">
                                    <p class="contact-message-preview">{{ \Illuminate\Support\Str::limit($contactMessage->message, 100) }}</p>
                                    <details class="contact-message-detail">
                                        <summary>
                                            <span>Read full message</span>
                                            <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                        </summary>
                                        <div class="contact-message-detail-body">{{ $contactMessage->message }}</div>
                                    </details>
                                </td>
                                <td>{{ $contactMessage->created_at->format('M d, Y') }}<small>{{ $contactMessage->created_at->format('g:i A') }}</small></td>
                                <td>
                                    <span class="user-status-badge {{ $contactMessage->is_read ? 'approved' : 'pending' }}">
                                        {{ $contactMessage->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="user-management-actions contact-message-actions">
                                        <form action="{{ route('admin.messages.toggle-read', $contactMessage) }}" method="POST">
                                            @csrf
                                            <button class="user-action-button {{ $contactMessage->is_read ? '' : 'approve' }}" type="submit">
                                                <i class="fa-solid {{ $contactMessage->is_read ? 'fa-envelope' : 'fa-check' }}" aria-hidden="true"></i>
                                                Mark {{ $contactMessage->is_read ? 'unread' : 'read' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('Delete this message? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="user-action-button delete" type="submit">
                                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="user-management-empty" colspan="6">
                                    @if ($hasFilters)
                                        No messages match these filters.
                                    @else
                                        No contact messages yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
            @if ($messages->hasPages())
                <div class="user-management-pagination">{{ $messages->links() }}</div>
            @endif
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
