<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit a parent recommendation for FEC International School">
    <title>Edit Parent Recommendation | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-testimonial-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container testimonial-management">
        <header class="testimonial-management-heading">
            <div>
                <a class="testimonial-management-back" href="{{ route('admin.testimonials.index') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Parent Recommendations
                </a>
                <h1>Edit Recommendation</h1>
                <p>Update the parent testimonial displayed on the Home page.</p>
            </div>
        </header>

        @if ($errors->any())
            <div class="testimonial-management-notice warning" role="alert">
                <strong>Please review the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="testimonial-management-panel testimonial-create-panel" aria-labelledby="testimonial-edit-title">
            <div class="testimonial-panel-heading">
                <span class="testimonial-panel-icon"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i></span>
                <div>
                    <h2 id="testimonial-edit-title">{{ $testimonial->parent_name }}</h2>
                    <p>Saved changes are shown on the Home page.</p>
                </div>
            </div>

            <form class="testimonial-form" action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST">
                @csrf
                @method('PUT')
                <label class="testimonial-field">
                    <span>Parent name</span>
                    <input type="text" name="parent_name" value="{{ old('parent_name', $testimonial->parent_name) }}" maxlength="255" required>
                </label>
                <label class="testimonial-field">
                    <span>Student context</span>
                    <input type="text" name="student_context" value="{{ old('student_context', $testimonial->student_context) }}" maxlength="255" required>
                </label>
                <label class="testimonial-field">
                    <span>Recommendation</span>
                    <textarea name="quote" rows="5" maxlength="2000" required>{{ old('quote', $testimonial->quote) }}</textarea>
                </label>
                <div class="testimonial-form-actions">
                    <a class="testimonial-button secondary" href="{{ route('admin.testimonials.index') }}">Cancel</a>
                    <button class="testimonial-button primary" type="submit">
                        <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save changes
                    </button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
