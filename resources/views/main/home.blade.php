<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEC Private School - Family Education Center</title>
    <meta name="description" content="FEC Private School - Family Education Center">
    <meta name="keywords" content="FEC Private School, Family Education Center, International School, Myanmar School">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="hero-section" style="background-image: url('{{ asset('image/fec_building.jpg') }}');">
        <div class="hero-overlay">
            <div class="hero-content">
                <h2 class="hero-title">FEC PRIVATE SCHOOL</h2>
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
                    <p class="section-text">FEC Private School is a premier international institution dedicated to nurturing young minds for a brighter future. With modernized learning spaces and Smart Learning Systems, we provide the best possible educational environment for every student. With a maximum capacity of only 15 students per class, all classrooms and resource rooms are designed as modernized learning spaces to provide the best possible educational environment.</p>
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
                <div class="program-item fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Early Years" class="program-img">
                    <h3>Early Years</h3>
                    <p>Foundation learning for young minds and personal development</p>
                </div>
                <div class="program-item fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Primary School" class="program-img">
                    <h3>Primary School</h3>
                    <p>Core academic and personal development</p>
                </div>
                <div class="program-item fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Secondary School" class="program-img">
                    <h3>Secondary School</h3>
                    <p>International curriculum for higher education</p>
                </div>
                <div class="program-item fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="IGCSE/GED" class="program-img">
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
            <div class="testimonials-grid">
                <div class="testimonial-card fade-in-up">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>FEC has provided an excellent foundation for my child. The small class sizes and individual attention have made a significant difference in their academic progress.</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>Daw Mya Mya</strong>
                        <span>Parent of Primary Student</span>
                    </div>
                </div>
                <div class="testimonial-card fade-in-up">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>The international curriculum at FEC is outstanding. My daughter has developed not only academically but also in confidence and character.</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>U Kyaw Kyaw</strong>
                        <span>Parent of Secondary Student</span>
                    </div>
                </div>
                <div class="testimonial-card fade-in-up">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>The teachers at FEC are truly dedicated. They go above and beyond to ensure each student reaches their full potential. Highly recommended!</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>Daw Su Su</strong>
                        <span>Parent of Early Years Student</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('partials.footer')
</body>
</html>