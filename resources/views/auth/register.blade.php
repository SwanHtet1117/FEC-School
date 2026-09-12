<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FEC Private School</title>
    <meta name="description" content="Register for FEC Private School portal.">
    <meta name="keywords" content="FEC Private School, Register, Sign Up">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="auth-hero fade-in-up">
        <h1>Register</h1>
        <p>Create your FEC Private School account</p>
    </section>

    <div class="auth-container fade-in-up">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Create Account</h2>
                <p>Fill in your details to get started</p>
            </div>

            <form action="#" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
                    </label>
                </div>

                <button type="submit" class="btn-auth-submit">
                    Create Account <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="auth-divider">
                <span>or register with</span>
            </div>

            <div class="social-login">
                <button class="social-btn google">
                    <i class="fab fa-google"></i>
                    <span>Google</span>
                </button>
                <button class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                    <span>Facebook</span>
                </button>
            </div>

            <div class="auth-footer">
                <p>Already have an account? <a href="/login">Login here</a></p>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
