<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FEC Private School</title>
    <meta name="description" content="Contact FEC Private School - Family Education Center. Get in touch with us for admissions, inquiries, and more.">
    <meta name="keywords" content="FEC Private School, Contact, Admissions, Inquiries">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="contact-hero fade-in-up">
        <h1>Contact Us</h1>
        <p>Get in Touch with FEC Private School</p>
    </section>

    <div class="contact-container">
        <div class="contact-info-card fade-in-left">
            <h2>Contact Information</h2>
            
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="contact-details">
                    <h4>Address</h4>
                    <p>Naypyidaw, Myanmar</p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="contact-details">
                    <h4>Phone</h4>
                    <p><a href="tel:+959513350838">+95 (9) 51 335 0838</a></p>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="contact-details">
                    <h4>Email</h4>
                    <p><a href="mailto:info@fecprivate-school.com">info@fecprivate-school.com</a></p>
                    <p><a href="mailto:admissions@fecprivate-school.com">admissions@fecprivate-school.com</a></p>
                </div>
            </div>

            <div class="office-hours">
                <h5><i class="fas fa-clock"></i> Office Hours</h5>
                <div class="office-hours-item">
                    <span>Monday – Friday</span>
                    <span>8:30 AM – 4:30 PM</span>
                </div>
                <div class="office-hours-item">
                    <span>Saturday – Sunday</span>
                    <span>9:00 AM – 12:00 PM</span>
                </div>
            </div>
        </div>
        <!-- Contact Form -->
        <div class="contact-form-card fade-in-right">
            <h2>Send a Message</h2>
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="admissions">Admissions Inquiry</option>
                        <option value="academic">Academic Programs</option>
                        <option value="general">General Inquiry</option>
                        <option value="careers">Career Opportunities</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" placeholder="Type your message here..." required></textarea>
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
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3818.6604855773!2d96.12916731475173!3d19.7636999866424!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c85456b7f3b2b7%3A0x6a6c6c6c6c6c6c6c!2sNaypyidaw%2C%20Myanmar!5e0!3m2!1sen!2sus!4v1234567890" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>
    <!-- Careers Section -->
    <section class="content-section">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Careers & Job Opportunities</h2>
            <p class="section-text fade-in-up">Join our team of dedicated educators and staff committed to excellence in education.</p>
            
            <div class="careers-grid fade-in-up">
                <div class="career-card">
                    <div class="career-header">
                        <div class="career-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <span class="career-type">Teaching</span>
                    </div>
                    <h3>English Teacher</h3>
                    <p class="career-department">Primary School</p>
                    <div class="career-details">
                        <span><i class="fas fa-clock"></i> Full-time</span>
                        <span><i class="fas fa-map-marker-alt"></i> Naypyidaw</span>
                    </div>
                    <p class="career-description">We are seeking an experienced English teacher to join our primary school team. Candidates should have strong communication skills and passion for teaching young learners.</p>
                    <a href="#" class="career-apply-btn">Apply Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="career-card">
                    <div class="career-header">
                        <div class="career-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <span class="career-type">Teaching</span>
                    </div>
                    <h3>Science Teacher</h3>
                    <p class="career-department">Secondary School</p>
                    <div class="career-details">
                        <span><i class="fas fa-clock"></i> Full-time</span>
                        <span><i class="fas fa-map-marker-alt"></i> Naypyidaw</span>
                    </div>
                    <p class="career-description">Looking for a qualified Science teacher with expertise in Physics, Chemistry, or Biology. Laboratory experience and innovative teaching methods required.</p>
                    <a href="#" class="career-apply-btn">Apply Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="career-card">
                    <div class="career-header">
                        <div class="career-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <span class="career-type">Teaching</span>
                    </div>
                    <h3>Mathematics Teacher</h3>
                    <p class="career-department">Secondary School</p>
                    <div class="career-details">
                        <span><i class="fas fa-clock"></i> Full-time</span>
                        <span><i class="fas fa-map-marker-alt"></i> Naypyidaw</span>
                    </div>
                    <p class="career-description">We need a passionate Mathematics teacher to inspire students. Strong background in algebra, geometry, and calculus preferred.</p>
                    <a href="#" class="career-apply-btn">Apply Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="career-card">
                    <div class="career-header">
                        <div class="career-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span class="career-type">Administration</span>
                    </div>
                    <h3>Administrative Assistant</h3>
                    <p class="career-department">School Office</p>
                    <div class="career-details">
                        <span><i class="fas fa-clock"></i> Full-time</span>
                        <span><i class="fas fa-map-marker-alt"></i> Naypyidaw</span>
                    </div>
                    <p class="career-description">Seeking an organized administrative assistant to support daily school operations. Excellent communication and computer skills required.</p>
                    <a href="#" class="career-apply-btn">Apply Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="career-card">
                    <div class="career-header">
                        <div class="career-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <span class="career-type">IT Support</span>
                    </div>
                    <h3>IT Technician</h3>
                    <p class="career-department">IT Department</p>
                    <div class="career-details">
                        <span><i class="fas fa-clock"></i> Full-time</span>
                        <span><i class="fas fa-map-marker-alt"></i> Naypyidaw</span>
                    </div>
                    <p class="career-description">Looking for an IT technician to maintain school computer systems, networks, and provide technical support to staff and students.</p>
                    <a href="#" class="career-apply-btn">Apply Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="career-card">
                    <div class="career-header">
                        <div class="career-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <span class="career-type">Student Services</span>
                    </div>
                    <h3>School Counselor</h3>
                    <p class="career-department">Student Services</p>
                    <div class="career-details">
                        <span><i class="fas fa-clock"></i> Full-time</span>
                        <span><i class="fas fa-map-marker-alt"></i> Naypyidaw</span>
                    </div>
                    <p class="career-description">We need a qualified school counselor to provide emotional and academic support to students. Psychology background and experience with children required.</p>
                    <a href="#" class="career-apply-btn">Apply Now <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>