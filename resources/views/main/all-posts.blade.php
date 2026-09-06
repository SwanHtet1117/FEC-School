<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts - FEC Private School</title>
    <meta name="description" content="Browse all news, events, and announcements from FEC Private School.">
    <meta name="keywords" content="FEC Private School, News, Events, Announcements, All Posts">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="news-events-hero fade-in-up">
        <h1>All Posts</h1>
        <p>Browse all news, events, and announcements from FEC Private School</p>
    </section>

    <div class="news-events-container">
        <div class="news-tabs fade-in-up">
            <button class="news-tab active">All Posts</button>
            <button class="news-tab">News</button>
            <button class="news-tab">Events</button>
            <button class="news-tab">Announcements</button>
        </div>

        <!-- All Posts Grid -->
        <div class="news-section">
            <div class="news-grid">
                <div class="news-card fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Student Achievement" class="news-card-image">
                    <span class="news-card-badge">News</span>
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> April 15, 2026
                        </div>
                        <h3 class="news-card-title">FEC Students Win National Science Competition</h3>
                        <p class="news-card-excerpt">Our students achieved remarkable success in the National Science Fair, bringing home multiple awards for their innovative projects.</p>
                        <a href="#" class="news-card-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="New Program" class="news-card-image">
                    <span class="news-card-badge">News</span>
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> April 10, 2026
                        </div>
                        <h3 class="news-card-title">Launch of Advanced STEM Program</h3>
                        <p class="news-card-excerpt">FEC Private School introduces cutting-edge STEM curriculum to prepare students for future technological challenges.</p>
                        <a href="#" class="news-card-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Achievement" class="news-card-image">
                    <span class="news-card-badge">News</span>
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> April 5, 2026
                        </div>
                        <h3 class="news-card-title">Teacher Recognition Award</h3>
                        <p class="news-card-excerpt">Our dedicated faculty member receives excellence in teaching award from the Ministry of Education.</p>
                        <a href="#" class="news-card-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Open Day" class="news-card-image">
                    <span class="news-card-badge">Event</span>
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> May 20, 2026
                        </div>
                        <h3 class="news-card-title">School Open Day</h3>
                        <p class="news-card-excerpt">Join us for our annual open day to explore our campus, meet our teachers, and learn about our programs.</p>
                        <div class="news-card-location">
                            <i class="fas fa-map-marker-alt"></i> FEC Campus, Naypyidaw
                        </div>
                        <a href="#" class="news-card-link">Register Now <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Sports Day" class="news-card-image">
                    <span class="news-card-badge">Event</span>
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> June 15, 2026
                        </div>
                        <h3 class="news-card-title">Annual Sports Day</h3>
                        <p class="news-card-excerpt">Celebrate athleticism and teamwork at our exciting sports day featuring various competitions and activities.</p>
                        <div class="news-card-location">
                            <i class="fas fa-map-marker-alt"></i> School Sports Ground
                        </div>
                        <a href="#" class="news-card-link">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Workshop" class="news-card-image">
                    <span class="news-card-badge">Event</span>
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> July 10, 2026
                        </div>
                        <h3 class="news-card-title">Parent-Teacher Workshop</h3>
                        <p class="news-card-excerpt">Interactive workshop for parents to understand modern teaching methods and student development.</p>
                        <div class="news-card-location">
                            <i class="fas fa-map-marker-alt"></i> Conference Hall
                        </div>
                        <a href="#" class="news-card-link">Register <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> April 20, 2026
                        </div>
                        <h3 class="news-card-title">School Holiday Notice</h3>
                        <p class="news-card-excerpt">School will be closed from April 25-28 for the upcoming holiday period. Classes resume on April 29.</p>
                        <div class="news-card-location">
                            <i class="fas fa-user"></i> Administration
                        </div>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> April 18, 2026
                        </div>
                        <h3 class="news-card-title">New Uniform Policy</h3>
                        <p class="news-card-excerpt">Updated uniform guidelines for the upcoming academic year. Please review the new dress code policy.</p>
                        <div class="news-card-location">
                            <i class="fas fa-user"></i> Administration
                        </div>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> April 15, 2026
                        </div>
                        <h3 class="news-card-title">Transportation Schedule Update</h3>
                        <p class="news-card-excerpt">New bus routes and schedules for school transportation. Please check the updated routes for your area.</p>
                        <div class="news-card-location">
                            <i class="fas fa-user"></i> Transport Department
                        </div>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Cultural Event" class="news-card-image">
                    <span class="news-card-badge">Event</span>
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> August 15, 2026
                        </div>
                        <h3 class="news-card-title">Cultural Festival</h3>
                        <p class="news-card-excerpt">Annual cultural celebration showcasing diverse traditions, performances, and artistic expressions from our community.</p>
                        <div class="news-card-location">
                            <i class="fas fa-map-marker-alt"></i> School Auditorium
                        </div>
                        <a href="#" class="news-card-link">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Graduation" class="news-card-image">
                    <span class="news-card-badge">News</span>
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> March 25, 2026
                        </div>
                        <h3 class="news-card-title">Graduation Ceremony 2026</h3>
                        <p class="news-card-excerpt">Celebrating the achievements of our graduating class as they embark on their next educational journey.</p>
                        <a href="#" class="news-card-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card fade-in-up">
                    <div class="news-card-content">
                        <div class="news-card-date">
                            <i class="fas fa-calendar"></i> April 12, 2026
                        </div>
                        <h3 class="news-card-title">Library Hours Extended</h3>
                        <p class="news-card-excerpt">School library will now remain open until 6 PM on weekdays to support student learning and research.</p>
                        <div class="news-card-location">
                            <i class="fas fa-user"></i> Library Department
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination fade-in-up">
                <button class="pagination-btn disabled"><i class="fas fa-chevron-left"></i> Previous</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">Next <i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
