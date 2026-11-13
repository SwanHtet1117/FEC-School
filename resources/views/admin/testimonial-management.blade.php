<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage parent recommendations for FEC International School">
    <title>Parent Recommendations | FEC International School</title>
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
                <a class="testimonial-management-back" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Admin Dashboard
                </a>
                <h1>Parent Recommendations</h1>
                <p>Add and maintain the testimonials displayed on the Home page.</p>
            </div>
            <span class="testimonial-management-count">
                {{ $testimonialCount }} {{ $testimonialCount === 1 ? 'Testimonial' : 'Testimonials' }}
            </span>
        </header>

        @if (session('status'))
            <p class="testimonial-management-notice success" role="status">{{ session('status') }}</p>
        @endif
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
        @if ($filterErrors->any())
            <div class="testimonial-management-notice warning" role="alert">
                <strong>Some filter values are invalid.</strong>
                <ul>
                    @foreach ($filterErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="testimonial-management-panel testimonial-create-panel" aria-labelledby="testimonial-create-title">
            <div class="testimonial-panel-heading">
                <span class="testimonial-panel-icon"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i></span>
                <div>
                    <h2 id="testimonial-create-title">Add a recommendation</h2>
                    <p>New recommendations are added to the end of the Home page list.</p>
                </div>
            </div>

            <form class="testimonial-form" action="{{ route('admin.testimonials.store') }}" method="POST">
                @csrf
                <label class="testimonial-field">
                    <span>Parent name</span>
                    <input type="text" name="parent_name" value="{{ old('parent_name') }}" maxlength="255" placeholder="e.g. Daw Mya Mya" required>
                </label>
                <label class="testimonial-field">
                    <span>Student context</span>
                    <input type="text" name="student_context" value="{{ old('student_context') }}" maxlength="255" placeholder="e.g. Parent of Primary Student" required>
                </label>
                <label class="testimonial-field">
                    <span>Recommendation</span>
                    <textarea name="quote" rows="4" maxlength="2000" placeholder="Write the parent's recommendation" required>{{ old('quote') }}</textarea>
                </label>
                <div class="testimonial-form-actions">
                    <button class="testimonial-button primary" type="submit">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i> Add recommendation
                    </button>
                </div>
            </form>
        </section>

        <section class="testimonial-management-panel" aria-labelledby="testimonial-list-title">
            <div class="testimonial-panel-heading">
                <span class="testimonial-panel-icon"><i class="fa-regular fa-comment-dots" aria-hidden="true"></i></span>
                <div>
                    <h2 id="testimonial-list-title">Current recommendations</h2>
                    <p>Updates are reflected on the Home page immediately.</p>
                </div>
            </div>

            <form class="testimonial-filter-form" action="{{ route('admin.testimonials.index') }}" method="GET">
                <label class="testimonial-filter-field testimonial-filter-search">
                    <span>Search recommendations</span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="Name, student context, or quote">
                </label>
                <label class="testimonial-filter-field">
                    <span>Student context</span>
                    <select name="student_context">
                        <option value="">All student contexts</option>
                        @foreach ($studentContexts as $studentContext)
                            <option value="{{ $studentContext }}" @selected(($filters['student_context'] ?? '') === $studentContext)>{{ $studentContext }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="testimonial-filter-actions">
                    <button class="testimonial-button primary" type="submit">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i> Filter
                    </button>
                    @if ($hasFilters)
                        <a class="testimonial-button secondary" href="{{ route('admin.testimonials.index') }}">Clear</a>
                    @endif
                </div>
            </form>

            <p class="testimonial-filter-result" role="status">
                Showing {{ $testimonials->count() }} of {{ $testimonialCount }} recommendations
            </p>

            <div class="testimonial-list">
                @forelse ($testimonials as $testimonial)
                    <article class="testimonial-item">
                        <div class="testimonial-item-copy">
                            <span class="testimonial-item-number">#{{ $loop->iteration }}</span>
                            <div>
                                <h3>{{ $testimonial->parent_name }}</h3>
                                <p class="testimonial-item-context">{{ $testimonial->student_context }}</p>
                                <p class="testimonial-item-quote">{{ $testimonial->quote }}</p>
                            </div>
                        </div>
                        <div class="testimonial-item-actions">
                            <a class="testimonial-button secondary" href="{{ route('admin.testimonials.edit', $testimonial) }}">
                                <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i> Edit
                            </a>
                            <form class="testimonial-delete-form" data-testimonial-name="{{ $testimonial->parent_name }}" action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="testimonial-button danger" type="submit" aria-label="Delete recommendation from {{ $testimonial->parent_name }}">
                                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="testimonial-empty">
                        @if ($filterErrors->any())
                            Please correct the filters above to view recommendations.
                        @elseif ($hasFilters)
                            No recommendations match these filters.
                        @else
                            No parent recommendations have been added yet.
                        @endif
                    </p>
                @endforelse
            </div>
        </section>

        <dialog class="testimonial-delete-dialog" id="testimonialDeleteDialog" aria-labelledby="testimonialDeleteTitle" aria-describedby="testimonialDeleteDescription">
            <div class="testimonial-delete-dialog-heading">
                <span class="testimonial-delete-dialog-icon"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></span>
                <div>
                    <h2 id="testimonialDeleteTitle">Delete this recommendation?</h2>
                    <p>This action cannot be undone.</p>
                </div>
            </div>
            <p class="testimonial-delete-dialog-description" id="testimonialDeleteDescription">
                You are about to delete the recommendation from <strong id="testimonialDeleteName"></strong>.
            </p>
            <div class="testimonial-delete-dialog-actions">
                <button class="testimonial-button secondary" id="testimonialDeleteCancel" type="button" autofocus>Keep recommendation</button>
                <button class="testimonial-button danger" id="testimonialDeleteConfirm" type="button">
                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Delete recommendation
                </button>
            </div>
        </dialog>
    </main>

    @include('partials.footer')
    <script>
        const testimonialDeleteDialog = document.getElementById('testimonialDeleteDialog');
        const testimonialDeleteName = document.getElementById('testimonialDeleteName');
        let pendingTestimonialDeleteForm = null;

        document.querySelectorAll('.testimonial-delete-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();
                pendingTestimonialDeleteForm = form;
                testimonialDeleteName.textContent = form.dataset.testimonialName;
                testimonialDeleteDialog.showModal();
            });
        });

        document.getElementById('testimonialDeleteCancel').addEventListener('click', () => {
            testimonialDeleteDialog.close();
        });

        document.getElementById('testimonialDeleteConfirm').addEventListener('click', () => {
            if (!pendingTestimonialDeleteForm) {
                return;
            }

            pendingTestimonialDeleteForm.dataset.confirmed = 'true';
            pendingTestimonialDeleteForm.requestSubmit();
        });

        testimonialDeleteDialog.addEventListener('close', () => {
            pendingTestimonialDeleteForm = null;
        });
    </script>
</body>
</html>
