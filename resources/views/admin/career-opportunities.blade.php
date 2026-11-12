<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Publish and manage career opportunities at FEC International School">
    <title>Career Opportunities | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-career-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container career-management">
        <header class="career-management-heading">
            <div>
                <a class="career-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <h1>Career Opportunities</h1>
                <p>Publish job openings and manage the positions shown on the contact page.</p>
            </div>
            <span class="career-management-count">
                {{ $openCount }} {{ $openCount === 1 ? 'Open Position' : 'Open Positions' }}
            </span>
        </header>

        @if (session('status'))
            <p class="career-management-notice success" role="status">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <div class="career-management-notice warning" role="alert">
                <strong>Please review the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="career-management-panel" aria-labelledby="career-upload-title">
            <div class="career-management-panel-heading">
                <span class="career-management-icon"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></span>
                <div>
                    <h2 id="career-upload-title">Post a job opportunity</h2>
                    <p>Published positions appear in the Careers section of the contact page.</p>
                </div>
            </div>

            <form class="career-upload-form" action="{{ route('admin.careers.store') }}" method="POST">
                @csrf
                <label class="career-field">
                    <span>Job title</span>
                    <input type="text" name="title" value="{{ old('title') }}" maxlength="255" placeholder="e.g. Primary English Teacher" required>
                </label>
                <label class="career-field">
                    <span>Department</span>
                    <input type="text" name="department" value="{{ old('department') }}" maxlength="255" placeholder="e.g. Primary School" required>
                </label>
                <label class="career-field">
                    <span>Employment type</span>
                    <select name="employment_type" required>
                        <option value="">Select employment type</option>
                        @foreach (['Full-time', 'Part-time', 'Contract'] as $employmentType)
                            <option value="{{ $employmentType }}" @selected(old('employment_type') === $employmentType)>{{ $employmentType }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="career-field">
                    <span>Location</span>
                    <input type="text" name="location" value="{{ old('location', 'Naypyidaw') }}" maxlength="255" required>
                </label>
                <label class="career-field career-field-wide">
                    <span>Job description</span>
                    <textarea name="description" maxlength="5000" rows="5" placeholder="Describe the role, qualifications, and responsibilities." required>{{ old('description') }}</textarea>
                </label>
                <label class="career-field">
                    <span>Application email</span>
                    <input type="email" name="application_email" value="{{ old('application_email') }}" maxlength="255" placeholder="careers@example.com" required>
                </label>
                <label class="career-field">
                    <span>Application deadline <small>(optional)</small></span>
                    <input type="date" name="closes_at" value="{{ old('closes_at') }}" min="{{ today()->toDateString() }}">
                </label>
                <div class="career-form-actions">
                    <button class="career-management-button" type="submit">
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Publish opportunity
                    </button>
                </div>
            </form>
        </section>

        <section class="career-management-panel" aria-labelledby="career-list-title">
            <div class="career-management-panel-heading">
                <span class="career-management-icon"><i class="fa-solid fa-list-check" aria-hidden="true"></i></span>
                <div>
                    <h2 id="career-list-title">Career Job Opportunities</h2>
                    <p>Expired listings are hidden from the contact page automatically.</p>
                </div>
            </div>

            <div class="career-opportunity-list">
                @forelse ($opportunities as $opportunity)
                    @php($isOpen = ! $opportunity->closes_at || $opportunity->closes_at->isToday() || $opportunity->closes_at->isFuture())
                    <article class="career-opportunity-item">
                        <div class="career-opportunity-copy">
                            <div class="career-opportunity-title">
                                <h3>{{ $opportunity->title }}</h3>
                                <span class="career-opportunity-status {{ $isOpen ? 'open' : 'closed' }}">{{ $isOpen ? 'Open' : 'Closed' }}</span>
                            </div>
                            <p class="career-opportunity-meta">
                                {{ $opportunity->department }} <span aria-hidden="true">·</span>
                                {{ $opportunity->employment_type }} <span aria-hidden="true">·</span>
                                {{ $opportunity->location }}
                            </p>
                            <p class="career-opportunity-description">{{ \Illuminate\Support\Str::limit($opportunity->description, 220) }}</p>
                            <p class="career-opportunity-deadline">
                                @if ($opportunity->closes_at)
                                    Deadline: {{ $opportunity->closes_at->format('M d, Y') }}
                                @else
                                    No application deadline
                                @endif
                            </p>
                        </div>
                        <div class="career-opportunity-actions">
                            <a class="career-edit-button" href="{{ route('admin.careers.edit', $opportunity) }}">
                                <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i> Edit
                            </a>
                            <form action="{{ route('admin.careers.destroy', $opportunity) }}" method="POST" onsubmit="return confirm('Delete this career opportunity? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button class="career-delete-button" type="submit" aria-label="Delete {{ $opportunity->title }}">
                                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="career-management-empty">No career opportunities have been posted yet.</p>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
