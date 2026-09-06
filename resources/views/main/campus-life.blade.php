<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Life - FEC Private School</title>
    <meta name="description" content="Explore campus life at FEC Private School - facilities, activities, and student community.">
    <meta name="keywords" content="FEC Private School, Campus Life, Facilities, Student Activities">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="campus-hero fade-in-up">
        <h1>Campus Life</h1>
        <p>Experience a vibrant community where learning extends beyond the classroom</p>
    </section>

    <div class="campus-container">
        <div class="campus-intro fade-in-up">
            <h2>Welcome to FEC Private School</h2>
            <p>Our campus is designed to inspire learning, foster creativity, and build community. With modern facilities, green spaces, and a supportive environment, students can explore their passions and develop lifelong friendships.</p>
        </div>

        <!-- Campus Locations -->
        <div class="campus-locations">
            <div class="campus-location-card fade-in-left">
                <img src="{{ asset('image/fec_building.jpg') }}" alt="Main Campus" class="campus-location-image">
                <div class="campus-location-content">
                    <h3>Main Campus - Naypyidaw</h3>
                    <p>Our primary campus features state-of-the-art classrooms, science labs, library, and sports facilities. Located in the heart of Naypyidaw, students enjoy easy access to city amenities while studying in a secure environment.</p>
                    <ul class="campus-location-features">
                        <li><i class="fas fa-check-circle"></i> Smart Classrooms</li>
                        <li><i class="fas fa-check-circle"></i> Science Laboratories</li>
                        <li><i class="fas fa-check-circle"></i> Library & Resource Center</li>
                        <li><i class="fas fa-check-circle"></i> Sports Complex</li>
                    </ul>
                    <a href="#" class="btn-campus">Explore Campus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="campus-location-card fade-in-right">
                <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Secondary Campus" class="campus-location-image">
                <div class="campus-location-content">
                    <h3>Early Years Campus</h3>
                    <p>Dedicated campus for our youngest learners with age-appropriate facilities, safe play areas, and specialized early childhood education spaces. The environment is designed to nurture curiosity and foundational development.</p>
                    <ul class="campus-location-features">
                        <li><i class="fas fa-check-circle"></i> Play-Based Learning Areas</li>
                        <li><i class="fas fa-check-circle"></i> Safe Outdoor Playgrounds</li>
                        <li><i class="fas fa-check-circle"></i> Art & Music Rooms</li>
                        <li><i class="fas fa-check-circle"></i> Parent Waiting Areas</li>
                    </ul>
                    <a href="#" class="btn-campus">Explore Campus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Facilities Section -->
        <div class="facilities-section">
            <h2 class="fade-in-up">World-Class Facilities</h2>
            <div class="facilities-grid">
                <div class="facility-card fade-in-up">
                    <div class="facility-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h4>Smart Classrooms</h4>
                    <p>Technology-enabled learning spaces with interactive displays and modern teaching equipment.</p>
                </div>
                <div class="facility-card fade-in-up">
                    <div class="facility-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h4>Science Labs</h4>
                    <p>Well-equipped laboratories for physics, chemistry, and biology experiments.</p>
                </div>
                <div class="facility-card fade-in-up">
                    <div class="facility-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h4>Library</h4>
                    <p>Extensive collection of books, digital resources, and quiet study areas.</p>
                </div>
                <div class="facility-card fade-in-up">
                    <div class="facility-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h4>Sports Complex</h4>
                    <p>Multi-purpose sports fields, gymnasium, and indoor activity spaces.</p>
                </div>
                <div class="facility-card fade-in-up">
                    <div class="facility-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h4>Computer Labs</h4>
                    <p>Modern computer facilities with high-speed internet and latest software.</p>
                </div>
                <div class="facility-card fade-in-up">
                    <div class="facility-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <h4>Music & Arts</h4>
                    <p>Dedicated spaces for music practice, art studios, and creative expression.</p>
                </div>
                <div class="facility-card fade-in-up">
                    <div class="facility-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4>Cafeteria</h4>
                    <p>Nutritious meal options in a clean, comfortable dining environment.</p>
                </div>
                <div class="facility-card fade-in-up">
                    <div class="facility-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Security</h4>
                    <p>24/7 security surveillance and safe campus environment for all students.</p>
                </div>
            </div>
        </div>

        <!-- Student Life Section -->
        <div class="student-life-section">
            <h2 class="fade-in-up">Student Life & Activities</h2>
            <div class="student-life-grid">
                <div class="student-life-card fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Sports Activities" class="student-life-image">
                    <div class="student-life-content">
                        <h4>Sports & Athletics</h4>
                        <p>Competitive sports teams, intramural leagues, and fitness programs to keep students active and healthy.</p>
                    </div>
                </div>
                <div class="student-life-card fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Clubs" class="student-life-image">
                    <div class="student-life-content">
                        <h4>Clubs & Societies</h4>
                        <p>Diverse student-led clubs including robotics, debate, environmental, and cultural organizations.</p>
                    </div>
                </div>
                <div class="student-life-card fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Community Service" class="student-life-image">
                    <div class="student-life-content">
                        <h4>Community Service</h4>
                        <p>Volunteer opportunities and service learning projects that develop empathy and citizenship.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Section -->
        <div class="gallery-section">
            <h2 class="fade-in-up">Campus Gallery</h2>
            <div class="gallery-grid">
                <div class="gallery-item fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Campus View 1">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Campus View 2">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Campus View 3">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Campus View 4">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Campus View 5">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Campus View 6">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item fade-in-up">
                    <img src="{{ asset('image/fec_building.jpg') }}" alt="Campus View 7">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
                <div class="gallery-item fade-in-up">
                    <img src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Campus View 8">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>