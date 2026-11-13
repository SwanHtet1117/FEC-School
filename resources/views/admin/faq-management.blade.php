<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage frequently asked questions for FEC International School">
    <title>FAQ Management | FEC International School</title>
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
                <a class="faq-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <h1>FAQ Management</h1>
                <p>Add and maintain the questions shown on the Admissions page.</p>
            </div>
            <span class="faq-management-count">
                {{ $faqCount }} {{ $faqCount === 1 ? 'FAQ' : 'FAQs' }}
            </span>
        </header>

        @if (session('status'))
            <p class="faq-management-notice success" role="status">{{ session('status') }}</p>
        @endif
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
        @if ($filterErrors->any())
            <div class="faq-management-notice warning" role="alert">
                <strong>Some filter values are invalid.</strong>
                <ul>
                    @foreach ($filterErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="faq-management-panel faq-create-panel" aria-labelledby="faq-create-title">
            <div class="faq-panel-heading">
                <span class="faq-panel-icon"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i></span>
                <div>
                    <h2 id="faq-create-title">Add a question</h2>
                    <p>New FAQs will appear at the end of the Admissions page list.</p>
                </div>
            </div>

            <form class="faq-form" action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <label class="faq-field">
                    <span>Question</span>
                    <input type="text" name="question" value="{{ old('question') }}" maxlength="255" placeholder="Enter a frequently asked question" required>
                </label>
                <label class="faq-field">
                    <span>Answer</span>
                    <textarea name="answer" rows="4" maxlength="5000" placeholder="Write a clear answer for families" required>{{ old('answer') }}</textarea>
                </label>
                <div class="faq-form-actions">
                    <button class="faq-button primary" type="submit">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i> Add FAQ
                    </button>
                </div>
            </form>
        </section>

        <section class="faq-management-panel" aria-labelledby="faq-list-title">
            <div class="faq-panel-heading">
                <span class="faq-panel-icon"><i class="fa-regular fa-circle-question" aria-hidden="true"></i></span>
                <div>
                    <h2 id="faq-list-title">Current FAQs</h2>
                    <p>Changes are reflected on the Admissions page immediately.</p>
                </div>
            </div>

            <form class="faq-filter-form" action="{{ route('admin.faqs.index') }}" method="GET">
                <label class="faq-filter-field">
                    <span>Search FAQs</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="Search questions or answers">
                </label>
                <div class="faq-filter-actions">
                    <button class="faq-button primary" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i> Search
                    </button>
                    @if ($hasFilters)
                        <a class="faq-button secondary" href="{{ route('admin.faqs.index') }}">Clear</a>
                    @endif
                </div>
            </form>

            <p class="faq-filter-result" role="status">
                Showing {{ $faqs->count() }} of {{ $faqCount }} FAQs
            </p>

            <div class="faq-list">
                @forelse ($faqs as $faq)
                    <article class="faq-item">
                        <div class="faq-item-copy">
                            <span class="faq-item-number">Q{{ $loop->iteration }}</span>
                            <div>
                                <h3>{{ $faq->question }}</h3>
                                <p>{{ $faq->answer }}</p>
                            </div>
                        </div>
                        <div class="faq-item-actions">
                            <a class="faq-button secondary" href="{{ route('admin.faqs.edit', $faq) }}">
                                <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i> Edit
                            </a>
                            <form class="faq-delete-form" data-faq-delete-question="{{ $faq->question }}" action="{{ route('admin.faqs.destroy', $faq) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="faq-button danger" type="submit" aria-label="Delete {{ $faq->question }}">
                                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="faq-empty">
                        @if ($filterErrors->any())
                            Please correct the filters above to view FAQs.
                        @elseif ($hasFilters)
                            No FAQs match this search.
                        @else
                            No FAQs have been added yet.
                        @endif
                    </p>
                @endforelse
            </div>
        </section>

        <dialog class="faq-delete-dialog" id="faqDeleteDialog" aria-labelledby="faqDeleteTitle" aria-describedby="faqDeleteDescription">
            <div class="faq-delete-dialog-heading">
                <span class="faq-delete-dialog-icon"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></span>
                <div>
                    <h2 id="faqDeleteTitle">Delete this FAQ?</h2>
                    <p>This action cannot be undone.</p>
                </div>
            </div>
            <p class="faq-delete-dialog-description" id="faqDeleteDescription">
                You are about to delete <strong id="faqDeleteQuestion"></strong>.
            </p>
            <div class="faq-delete-dialog-actions">
                <button class="faq-button secondary" id="faqDeleteCancel" type="button" autofocus>Keep FAQ</button>
                <button class="faq-button danger" id="faqDeleteConfirm" type="button">
                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Delete FAQ
                </button>
            </div>
        </dialog>
    </main>

    @include('partials.footer')
    <script>
        const faqDeleteDialog = document.getElementById('faqDeleteDialog');
        const faqDeleteQuestion = document.getElementById('faqDeleteQuestion');
        let pendingFaqDeleteForm = null;

        document.querySelectorAll('.faq-delete-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();
                pendingFaqDeleteForm = form;
                faqDeleteQuestion.textContent = form.dataset.faqDeleteQuestion;
                faqDeleteDialog.showModal();
            });
        });

        document.getElementById('faqDeleteCancel').addEventListener('click', () => {
            faqDeleteDialog.close();
        });

        document.getElementById('faqDeleteConfirm').addEventListener('click', () => {
            if (!pendingFaqDeleteForm) {
                return;
            }

            pendingFaqDeleteForm.dataset.confirmed = 'true';
            pendingFaqDeleteForm.requestSubmit();
        });

        faqDeleteDialog.addEventListener('close', () => {
            pendingFaqDeleteForm = null;
        });
    </script>
</body>
</html>
