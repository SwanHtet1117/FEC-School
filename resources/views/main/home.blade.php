<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEC International School - Family Education Century</title>
    <meta name="description" content="FEC International School - Family Education Center">
    <meta name="keywords" content="FEC International School, Family Education Center, International School, Myanmar School">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    @if (session('status'))
        <p class="registration-status" role="status">{{ session('status') }}</p>
    @endif

    <section class="hero-section" data-slides="{{ $heroSlides->map(fn ($slide) => $slide->image_url)->implode('|') }}">
        <div class="hero-overlay">
            <div class="hero-content">
                <h2 class="hero-title">FEC INTERNATIONAL SCHOOL</h2>
                <p class="hero-subtitle">NURTURING YOUNG MINDS FOR A BRIGHTER CENTURY</p>
                <a href="#" class="btn-apply hero-button">APPLY NOW <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="section-container">
            <div class="about-layout">
                <div class="about-content fade-in-left">
                    <h2 class="section-title">About Our School</h2>
                    <p class="section-text">FEC International School is a premier international institution dedicated to nurturing young minds for a brighter future. With modernized learning spaces and Smart Learning Systems, we provide the best possible educational environment for every student. With a maximum capacity of only 15 students per class, all classrooms and resource rooms are designed as modernized learning spaces to provide the best possible educational environment.</p>
                    <a href="/about" class="section-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="about-image fade-in-right">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="FEC School Building">
                </div>
            </div>
        </div>
    </section>

    <section class="content-section light-bg">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Academic Programs</h2>
            <p class="section-subtitle fade-in-up">Structured learning pathways for every stage</p>
            <div class="programs-grid">
                <div class="program-item program-item-home fade-in-up">
                    <img src="{{ asset('image/early_student_activity_01.jpg') }}" alt="Early Years students celebrating together" class="program-img">
                    <h3>Early Years</h3>
                    <p>Foundation learning for young minds and personal development</p>
                </div>
                <div class="program-item program-item-home fade-in-up">
                    <img src="{{ asset('image/primary_student_activity_01.jpg') }}" alt="Primary students working in their classroom" class="program-img">
                    <h3>Primary School</h3>
                    <p>Core academic and personal development</p>
                </div>
                <div class="program-item program-item-home fade-in-up">
                    <img src="{{ asset('image/secondary_student_activity_01.jpg') }}" alt="Secondary students attending a school assembly" class="program-img">
                    <h3>Secondary School</h3>
                    <p>International curriculum for higher education</p>
                </div>
                <div class="program-item program-item-home fade-in-up">
                    <img src="{{ asset('image/ged_student_activity_01.jpg') }}" alt="GED students participating in a school event" class="program-img">
                    <h3>IGCSE/GED</h3>
                    <p>Advanced secondary qualifications and pathways</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Admission News</h2>
            <p class="section-text fade-in-up">Admissions are open for the upcoming academic year. Learn about entry requirements, important dates, and the application process.</p>
            <ul class="admission-list fade-in-up">
                <li><i class="fas fa-check-circle"></i> Open enrollment for Early Years to Secondary</li>
                <li><i class="fas fa-check-circle"></i> Flexible intake and assessment schedules</li>
                <li><i class="fas fa-check-circle"></i> Scholarship and financial aid information available</li>
            </ul>
            <a href="#" class="btn-apply-admission fade-in-up">Apply Now <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>

    <section class="content-section light-bg">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Why Choose FEC</h2>
            <div class="why-grid">
                <div class="why-card fade-in-up">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Small Class Sizes</h3>
                    <p>Maximum 15 students per class for personalized attention</p>
                </div>
                <div class="why-card fade-in-up">
                    <i class="fas fa-laptop"></i>
                    <h3>Smart Learning</h3>
                    <p>Modern classrooms equipped with Smart Learning Systems</p>
                </div>
                <div class="why-card fade-in-up">
                    <i class="fas fa-user-graduate"></i>
                    <h3>Individual Support</h3>
                    <p>Tailored extra classes for each student's learning style</p>
                </div>
                <div class="why-card fade-in-up">
                    <i class="fas fa-globe"></i>
                    <h3>International Curriculum</h3>
                    <p>UK (Pearson)-based international education standards</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Latest News & Events</h2>
            <p class="section-subtitle fade-in-up">Recent updates and highlights from around campus</p>
            <div class="news-grid">
                <div class="news-item fade-in-up">
                    <span class="news-date">Apr 04, 2026</span>
                    <h3>Student Enrollment</h3>
                    <p>New academic year registration now open</p>
                    <a href="/news-events" class="news-link">Read More</a>
                </div>
                <div class="news-item fade-in-up">
                    <span class="news-date">Apr 15, 2026</span>
                    <h3>Open Day</h3>
                    <p>Campus tour and orientation for prospective families</p>
                    <a href="/news-events" class="news-link">Read More</a>
                </div>
                <div class="news-item fade-in-up">
                    <span class="news-date">Apr 20, 2026</span>
                    <h3>Parent Meeting</h3>
                    <p>Academic progress discussion with teachers</p>
                    <a href="/news-events" class="news-link">Read More</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section light-bg">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Parent Recommendations</h2>
            <p class="section-subtitle fade-in-up">Hear from our parents about their experiences at FEC</p>
            <div class="testimonial-carousel" data-testimonial-carousel>
                <div class="testimonials-grid">
                    @forelse ($testimonials as $testimonial)
                        <article class="testimonial-card fade-in-up" data-testimonial-card @if ($loop->iteration > 3) hidden @endif>
                            <div class="testimonial-content">
                                <i class="fas fa-quote-left" aria-hidden="true"></i>
                                <p>{{ $testimonial->quote }}</p>
                            </div>
                            <div class="testimonial-author">
                                <strong>{{ $testimonial->parent_name }}</strong>
                                <span>{{ $testimonial->student_context }}</span>
                            </div>
                        </article>
                    @empty
                        <p>No parent recommendations are available at this time.</p>
                    @endforelse
                </div>
                @if ($testimonials->isNotEmpty())
                    <nav class="testimonials-carousel-controls" aria-label="Parent recommendation navigation">
                        <button class="testimonials-carousel-button" type="button" data-testimonial-previous aria-label="Previous recommendations" @disabled($testimonials->count() <= 3)>
                            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Previous
                        </button>
                        <span class="testimonials-carousel-range" aria-live="polite" data-testimonial-range>
                            1–{{ min(3, $testimonials->count()) }} of {{ $testimonials->count() }} recommendations
                        </span>
                        <button class="testimonials-carousel-button" type="button" data-testimonial-next aria-label="Next recommendations" @disabled($testimonials->count() <= 3)>
                            Next <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </nav>
                @endif
            </div>
        </div>
    </section>
    @include('partials.footer')
    <script>
        const testimonialCarousel = document.querySelector('[data-testimonial-carousel]');

        if (testimonialCarousel && testimonialCarousel.querySelector('[data-testimonial-previous]')) {
            const testimonialCards = Array.from(testimonialCarousel.querySelectorAll('[data-testimonial-card]'));
            const previousTestimonialsButton = testimonialCarousel.querySelector('[data-testimonial-previous]');
            const nextTestimonialsButton = testimonialCarousel.querySelector('[data-testimonial-next]');
            const testimonialRange = testimonialCarousel.querySelector('[data-testimonial-range]');
            const pageSize = 3;
            const pageCount = Math.ceil(testimonialCards.length / pageSize);
            let currentPage = 0;

            const showTestimonialPage = () => {
                const firstCard = currentPage * pageSize;
                const lastCard = Math.min(firstCard + pageSize, testimonialCards.length);

                testimonialCards.forEach((card, index) => {
                    card.hidden = index < firstCard || index >= lastCard;
                });

                previousTestimonialsButton.disabled = currentPage === 0;
                nextTestimonialsButton.disabled = currentPage >= pageCount - 1;
                testimonialRange.textContent = `${firstCard + 1}–${lastCard} of ${testimonialCards.length} recommendations`;
            };

            previousTestimonialsButton.addEventListener('click', () => {
                if (currentPage > 0) {
                    currentPage -= 1;
                    showTestimonialPage();
                }
            });

            nextTestimonialsButton.addEventListener('click', () => {
                if (currentPage < pageCount - 1) {
                    currentPage += 1;
                    showTestimonialPage();
                }
            });

            showTestimonialPage();
        }
    </script>
</body>
</html>