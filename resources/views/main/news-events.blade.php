<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Events - FEC Private School</title>
    <meta name="description" content="Stay updated with FEC Private School's latest news, events, announcements, and calendar.">
    <meta name="keywords" content="FEC Private School, News, Events, Announcements, Calendar">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="news-events-hero fade-in-up">
        <h1>News & Events</h1>
        <p>Stay updated with FEC Private School's latest announcements, student achievements, and upcoming activities.</p>
    </section>

    <div class="news-events-container">
        <div class="news-tabs fade-in-up">
            <button class="news-tab active">All Events</button>
            <button class="news-tab">Upcoming Events</button>
            <button class="news-tab">News</button>
            <button class="news-tab">Announcements</button>
            <button class="news-tab">Calendar</button>
        </div>

        <!-- Announcement Banner -->
        <div class="announcement-banner fade-in-up">
            <h3><i class="fas fa-bullhorn"></i> Important Announcement</h3>
            <p>Academic Year 2026-2027 Admission Now Open - Apply Before June 30th!</p>
        </div>

        <!-- News Section -->
        <div class="news-section">
            <h2 class="news-section-title fade-in-up">Latest News</h2>
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
            </div>
            <div class="view-all-link fade-in-up">
                <a href="/news-events/all-posts" class="btn-view-all">View All News <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Events Section -->
        <div class="news-section">
            <h2 class="news-section-title fade-in-up">Upcoming Events</h2>
            <div class="news-grid">
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
            </div>
            <div class="view-all-link fade-in-up">
                <a href="/news-events/all-posts" class="btn-view-all">View All Events <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Event Calendar Section -->
        <div class="news-section">
            <h2 class="news-section-title fade-in-up">Event Calendar</h2>
            <div class="event-calendar fade-in-up">
                <div class="calendar-header">
                    <h3 class="calendar-title">June 2026</h3>
                    <div class="calendar-nav">
                        <button><i class="fas fa-chevron-left"></i> Previous</button>
                        <button>Next <i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="calendar-grid">
                    <div class="calendar-day-header">Sun</div>
                    <div class="calendar-day-header">Mon</div>
                    <div class="calendar-day-header">Tue</div>
                    <div class="calendar-day-header">Wed</div>
                    <div class="calendar-day-header">Thu</div>
                    <div class="calendar-day-header">Fri</div>
                    <div class="calendar-day-header">Sat</div>
                    
                    <!-- Calendar Days -->
                    <div class="calendar-day"></div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">1</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">2</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">3</div>
                        <div class="calendar-event">Staff Meeting</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">4</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">5</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">6</div>
                    </div>
                    
                    <div class="calendar-day">
                        <div class="calendar-day-number">7</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">8</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">9</div>
                        <div class="calendar-event announcement">Exam</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">10</div>
                        <div class="calendar-event announcement">Exam</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">11</div>
                        <div class="calendar-event announcement">Exam</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">12</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">13</div>
                    </div>
                    
                    <div class="calendar-day">
                        <div class="calendar-day-number">14</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">15</div>
                        <div class="calendar-event">Sports Day</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">16</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">17</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">18</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">19</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">20</div>
                    </div>
                    
                    <div class="calendar-day">
                        <div class="calendar-day-number">21</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">22</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">23</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">24</div>
                        <div class="calendar-event">Parent Meeting</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">25</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">26</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">27</div>
                    </div>
                    
                    <div class="calendar-day">
                        <div class="calendar-day-number">28</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">29</div>
                    </div>
                    <div class="calendar-day">
                        <div class="calendar-day-number">30</div>
                        <div class="calendar-event announcement">Last Day</div>
                    </div>
                    <div class="calendar-day"></div>
                    <div class="calendar-day"></div>
                    <div class="calendar-day"></div>
                    <div class="calendar-day"></div>
                </div>
            </div>
        </div>

        <!-- Announcements Section -->
        <div class="news-section">
            <h2 class="news-section-title fade-in-up">Recent Announcements</h2>
            <div class="news-grid">
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
            </div>
        </div>
        <div class="view-all-link fade-in-up">
            <a href="/news-events/all-posts" class="btn-view-all">View All Announcements <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>