<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FEC International School</title>
    <meta name="description" content="Login to FEC International School portal.">
    <meta name="keywords" content="FEC International School, Login, Portal">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    
    @include('partials.header')

    <section class="auth-hero fade-in-up">
        <h1>Login</h1>
        <p>Access your FEC International School portal</p>
    </section>

    <div class="auth-container fade-in-up">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your FEC International School account.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" autocomplete="username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" value="1">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-auth-submit">
                    Login <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="/register">Register here</a></p>
                <p>Administrator access is managed separately by the site owner.</p>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
