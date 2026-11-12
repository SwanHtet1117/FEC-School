<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FEC International School</title>
    <meta name="description" content="Contact FEC International School - Family Education Center. Get in touch with us for admissions, inquiries, and more.">
    <meta name="keywords" content="FEC International School, Contact, Admissions, Inquiries">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="contact-hero fade-in-up">
        <h1>Contact Us</h1>
        <p>Get in Touch with FEC International School</p>
    </section>
    <!-- Contact Container -->
    <div class="contact-container">
        <!-- Contact Information Card -->
        <div class="contact-info-card fade-in-left">
            <h2>Contact Information</h2>
            
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="contact-details">
                    <h4>Address</h4>
                    <p>No.(PS-001, PS-007, PS-008) </p>
                    <p>The Conner of Chindwin Street & Hninsi(5) Street</p>
                    <p>Mingalar Depa Ward, Pobbathiri Township, Naypyitaw, Myanmar</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="contact-details">
                    <h4>Phone</h4>
                    <p><a href="tel:+959400336885">+95 (9) 400336885</a></p>
                    <p><a href="tel:+959400336882">+95 (9) 400336882</a></p>
                    <p><a href="tel:+959400336882">+95 (9) 400336882 (Viber)</a></p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="contact-details">
                    <h4>Email</h4>
                    <p><a href="mailto:kzhlaing@familyeducationcentury-fec.edu.mm">kzhlaing@familyeducationcentury-fec.edu.mm</a></p>
                    <p><a href="mailto:swnaing@familyeducationcentury-fec.edu.mm">swnaing@familyeducationcentury-fec.edu.mm</a></p>
                </div>
            </div>

            <div class="office-hours">
                <h5><i class="fas fa-clock"></i> Office Hours</h5>
                <div class="office-hours-item">
                    <span>Monday – Friday</span>
                    <span>8:00 AM – 3:00 PM</span>
                </div>
                <div class="office-hours-item">
                    <span>Saturday – Sunday</span>
                    <span>Closed</span>
                </div>
            </div>
        </div>
        <!-- Contact Form -->
        <div class="contact-form-card fade-in-right">
            <h2>Send a Message</h2>
            @if (session('status'))
                <p class="contact-form-status" role="status">{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <div class="contact-form-status error" role="alert">
                    <p>Please correct the following:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('contact.messages.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" maxlength="255" autocomplete="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" maxlength="255" autocomplete="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" maxlength="30" autocomplete="tel" pattern="[0-9+\s().-]+" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="admissions" @selected(old('subject') === 'admissions')>Admissions Inquiry</option>
                        <option value="academic" @selected(old('subject') === 'academic')>Academic Programs</option>
                        <option value="general" @selected(old('subject') === 'general')>General Inquiry</option>
                        <option value="careers" @selected(old('subject') === 'careers')>Career Opportunities</option>
                        <option value="other" @selected(old('subject') === 'other')>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" maxlength="10000" placeholder="Type your message here..." required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn-submit">
                    Send Message <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
    <!-- Map Section -->
    <section class="map-section fade-in-up">
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d35702.49544126126!2d96.16424893030558!3d19.849803913752414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c8b9f016c4abf1%3A0xf73b870f78bc0fcd!2sFEC%20Private%20School!5e0!3m2!1sen!2smm!4v1789284542874!5m2!1sen!2smm" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
    </section>
    <!-- Careers Section -->
    <section class="content-section">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Careers & Job Opportunities</h2>
            <p class="section-text fade-in-up">Join our team of dedicated educators and staff committed to excellence in education.</p>
            
            <div class="careers-grid fade-in-up">
                @forelse ($careerOpportunities as $opportunity)
                    <article class="career-card">
                        <div class="career-header">
                            <div class="career-icon">
                                <i class="fas fa-briefcase" aria-hidden="true"></i>
                            </div>
                            <span class="career-type">{{ $opportunity->employment_type }}</span>
                        </div>
                        <h3>{{ $opportunity->title }}</h3>
                        <p class="career-department">{{ $opportunity->department }}</p>
                        <div class="career-details">
                            <span><i class="fas fa-clock" aria-hidden="true"></i> {{ $opportunity->employment_type }}</span>
                            <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $opportunity->location }}</span>
                        </div>
                        <p class="career-description">{{ $opportunity->description }}</p>
                        <a href="mailto:{{ $opportunity->application_email }}?subject={{ rawurlencode('Application: '.$opportunity->title) }}" class="career-apply-btn">
                            Apply Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </article>
                @empty
                    <p class="career-empty-state">There are no current openings. Please check back soon.</p>
                @endforelse
            </div>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>