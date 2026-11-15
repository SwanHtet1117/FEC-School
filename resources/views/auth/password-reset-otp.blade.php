<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code | FEC International School</title>
    <meta name="description" content="Verify your FEC password reset request with an email code.">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('partials.header')

    <section class="auth-hero">
        <h1>Password Reset</h1>
        <p>Verify your request to choose a new password.</p>
    </section>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Enter Your Reset Code</h2>
                <p>If an account exists for that email, a six-digit code was sent. The code expires after {{ (int) config('auth.password_reset_otp.expire', 10) }} minutes.</p>
            </div>

            @if (session('status'))
                <p class="registration-status" role="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($expired)
                <p class="registration-status" role="status">This code has expired. Request a new code to continue.</p>
            @else
                <form action="{{ route('password.otp.verify') }}" method="POST" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <label for="code">Six-Digit Code</label>
                        <div class="input-with-icon">
                            <i class="fas fa-key" aria-hidden="true"></i>
                            <input type="text" id="code" name="code" value="{{ old('code') }}" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" placeholder="Enter your code" required autofocus>
                        </div>
                    </div>
                    <button type="submit" class="btn-auth-submit">
                        Verify Code <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
            @endif

            <form action="{{ route('password.otp.resend') }}" method="POST" class="auth-form">
                @csrf
                <button type="submit" class="btn-auth-submit">
                    Send a New Code <i class="fas fa-envelope" aria-hidden="true"></i>
                </button>
            </form>

            <div class="auth-footer">
                <p><a href="{{ route('password.request') }}">Start over</a></p>
                <p><a href="{{ route('login') }}">Return to login</a></p>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
