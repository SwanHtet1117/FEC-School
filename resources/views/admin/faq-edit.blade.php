<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit a frequently asked question for FEC International School">
    <title>Edit FAQ | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-faq-management.css') }}">
</head>
<body class="admin-dashboard">
    @include('partials.header')

    <main class="dashboard-container faq-management">
        <header class="faq-management-heading">
            <div>
                <a class="faq-management-back" href="{{ route('admin.faqs.index') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> FAQ Management
                </a>
                <h1>Edit FAQ</h1>
                <p>Update the question and answer shown on the Admissions page.</p>
            </div>
        </header>

        @if ($errors->any())
            <div class="faq-management-notice warning" role="alert">
                <strong>Please review the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="faq-management-panel faq-create-panel" aria-labelledby="faq-edit-title">
            <div class="faq-panel-heading">
                <span class="faq-panel-icon"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i></span>
                <div>
                    <h2 id="faq-edit-title">Update FAQ</h2>
                    <p>Saved changes will appear on the Admissions page immediately.</p>
                </div>
            </div>

            <form class="faq-form" action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                @csrf
                @method('PUT')
                <label class="faq-field">
                    <span>Question</span>
                    <input type="text" name="question" value="{{ old('question', $faq->question) }}" maxlength="255" required>
                </label>
                <label class="faq-field">
                    <span>Answer</span>
                    <textarea name="answer" rows="5" maxlength="5000" required>{{ old('answer', $faq->answer) }}</textarea>
                </label>
                <div class="faq-form-actions">
                    <a class="faq-button secondary" href="{{ route('admin.faqs.index') }}">Cancel</a>
                    <button class="faq-button primary" type="submit">
                        <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i> Save changes
                    </button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
