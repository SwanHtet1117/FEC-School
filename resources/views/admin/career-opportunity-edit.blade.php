<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit a career opportunity at FEC International School">
    <title>Edit Career Opportunity | FEC International School</title>
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
                <a class="career-management-back" href="{{ route('admin.careers.index') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Career Opportunities
                </a>
                <h1>Edit Career Opportunity</h1>
                <p>Update the job details displayed on the contact page.</p>
            </div>
        </header>

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

        <section class="career-management-panel" aria-labelledby="career-edit-title">
            <div class="career-management-panel-heading">
                <span class="career-management-icon"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i></span>
                <div>
                    <h2 id="career-edit-title">{{ $opportunity->title }}</h2>
                    <p>Changes will appear on the contact page after saving.</p>
                </div>
            </div>

            <form class="career-upload-form" action="{{ route('admin.careers.update', $opportunity) }}" method="POST">
                @csrf
                @method('PUT')
                <label class="career-field">
                    <span>Job title</span>
                    <input type="text" name="title" value="{{ old('title', $opportunity->title) }}" maxlength="255" required>
                </label>
                <label class="career-field">
                    <span>Department</span>
                    <input type="text" name="department" value="{{ old('department', $opportunity->department) }}" maxlength="255" required>
                </label>
                <label class="career-field">
                    <span>Employment type</span>
                    <select name="employment_type" required>
                        @foreach (['Full-time', 'Part-time', 'Contract'] as $employmentType)
                            <option value="{{ $employmentType }}" @selected(old('employment_type', $opportunity->employment_type) === $employmentType)>{{ $employmentType }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="career-field">
                    <span>Location</span>
                    <input type="text" name="location" value="{{ old('location', $opportunity->location) }}" maxlength="255" required>
                </label>
                <label class="career-field career-field-wide">
                    <span>Job description</span>
                    <textarea name="description" maxlength="5000" rows="5" required>{{ old('description', $opportunity->description) }}</textarea>
                </label>
                <label class="career-field">
                    <span>Application email</span>
                    <input type="email" name="application_email" value="{{ old('application_email', $opportunity->application_email) }}" maxlength="255" required>
                </label>
                <label class="career-field">
                    <span>Application deadline <small>(optional)</small></span>
                    <input type="date" name="closes_at" value="{{ old('closes_at', $opportunity->closes_at?->format('Y-m-d')) }}">
                </label>
                <div class="career-form-actions">
                    <a class="career-cancel-button" href="{{ route('admin.careers.index') }}">Cancel</a>
                    <button class="career-management-button" type="submit">
                        <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save changes
                    </button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
