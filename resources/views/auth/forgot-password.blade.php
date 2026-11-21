<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | FEC International School</title>
    <meta name="description" content="Reset your FEC International School account password using an email code.">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-page auth-forgot-password-page">
    @include('partials.header')

    <section class="auth-hero">
        <h1>Forgot Password</h1>
        <p>Request a one-time email code to reset your password.</p>
    </section>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Reset your password</h2>
                <p>Enter your account email. If it is registered, we will send a six-digit reset code.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger auth-login-error" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="255" placeholder="Enter your email" autocomplete="email" required autofocus>
                    </div>
                </div>
                <button type="submit" class="btn-auth-submit">
                    Send Reset Code <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            <div class="auth-footer">
                <p><a href="{{ route('login') }}">Return to login</a></p>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
