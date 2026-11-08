<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FEC International School</title>
    <meta name="description" content="Register for FEC International School portal.">
    <meta name="keywords" content="FEC International School, Register, Sign Up">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="auth-hero fade-in-up">
        <h1>Register</h1>
        <p>Create your FEC International School account</p>
    </section>

    <div class="auth-container fade-in-up">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Create Account</h2>
                <p>Fill in your details to get started. An administrator must approve your account before you can sign in.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" maxlength="100" placeholder="Enter your first name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name" maxlength="100" placeholder="Enter your last name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="30" placeholder="Enter your phone number">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" autocomplete="new-password" placeholder="Create a password (at least 8 characters)" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="password_confirmation" autocomplete="new-password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="information_confirmed" value="1" required>
                        <span>I confirm that the information above is accurate.</span>
                    </label>
                </div>

                <button type="submit" class="btn-auth-submit">
                    Create Account <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="/login">Login here</a></p>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
