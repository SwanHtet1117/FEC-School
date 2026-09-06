<header class="site-header">
        <div class="top-bar">
            <div class="top-bar-left">
                <span>FEC Private School | International School</span>
                <span class="contact-info"><i class="fas fa-phone"></i> +95 (9) 51 335 0838</span>
                <span class="contact-info"><i class="fas fa-envelope"></i> info@fecprivate-school.com</span>
            </div>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <nav class="main-nav">
            <div class="logo-container">
                <img src="{{ asset('image/logo_01.png') }}" alt="FEC Logo" class="logo">
                <div class="logo-text">
                    <h1>FEC</h1>
                    <span>FAMILY EDUCATION CENTER</span>
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="/about" class="{{ request()->is('about*') ? 'active' : '' }}">About Us</a></li>
                <li class="dropdown {{ request()->is('academics*') ? 'active' : '' }}"><a href="/academics">Academics <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="/academics" class="{{ request()->is('academics') ? 'active' : '' }}">Academic Overview</a></li>
                        <li><a href="/academics/early-years" class="{{ request()->is('academics/early-years*') ? 'active' : '' }}">Early Years</a></li>
                        <li><a href="/academics/primary" class="{{ request()->is('academics/primary*') ? 'active' : '' }}">Primary School</a></li>
                        <li><a href="/academics/secondary" class="{{ request()->is('academics/secondary*') ? 'active' : '' }}">Secondary School</a></li>
                        <li><a href="/academics/ged-igcse" class="{{ request()->is('academics/ged-igcse*') ? 'active' : '' }}">GED/IGCSE</a></li>
                    </ul>
                </li>
                <li><a href="/admissions" class="{{ request()->is('admissions*') ? 'active' : '' }}">Admission</a></li>
                <li><a href="/campus-life" class="{{ request()->is('campus-life*') ? 'active' : '' }}">Campus Life</a></li>
                <li class="dropdown {{ request()->is('gallery/*') ? 'active' : '' }}"><a href="#">Gallery <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="/gallery/photo" class="{{ request()->is('gallery/photo*') ? 'active' : '' }}">Photo Gallery</a></li>
                        <li><a href="/gallery/video" class="{{ request()->is('gallery/video*') ? 'active' : '' }}">Video Gallery</a></li>
                    </ul>
                </li>
                <li><a href="/news-events" class="{{ request()->is('news-events*') ? 'active' : '' }}">News & Events</a></li>
                <li><a href="/contact" class="{{ request()->is('contact*') ? 'active' : '' }}">Contact</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="#" class="btn-login">Login</a>
            </div>
        </nav>
    </header>