<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Sign-in Code | FEC International School</title>
    <meta name="description" content="Confirm your FEC International School sign-in with an email code.">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
    <script src="{{ asset('js/otp-countdown.js') }}" defer></script>
</head>
<body>
    @include('partials.header')

    <section class="auth-hero fade-in-up">
        <h1>Email Verification</h1>
        <p>Complete your sign-in securely.</p>
    </section>

    <div class="auth-container fade-in-up">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Enter Your One-Time Code</h2>
                <p>We sent a six-digit code to the email address on your account.</p>
                @unless ($expired)
                    <p class="otp-expiration-notice" role="timer" aria-live="off" data-otp-countdown data-otp-countdown-seconds="{{ $remainingSeconds }}">
                        <i class="fas fa-clock" aria-hidden="true"></i>
                        <span><span data-otp-countdown-label>Code expires in</span> <strong data-otp-countdown-display>{{ sprintf('%02d:%02d', intdiv($remainingSeconds, 60), $remainingSeconds % 60) }}</strong></span>
                    </p>
                @endunless
            </div>

            @if (session('status'))
                <p class="registration-status" role="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger auth-login-error" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($expired)
                <p class="registration-status" role="status">This code has expired. Request a new code to continue.</p>
            @else
                <form action="{{ route('login.otp.verify') }}" method="POST" class="auth-form" data-otp-verification-form>
                    @csrf
                    <div class="form-group">
                        <label for="code">Six-Digit Code</label>
                        <div class="input-with-icon">
                            <i class="fas fa-key" aria-hidden="true"></i>
                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                placeholder="Enter your code"
                                required
                                autofocus
                            >
                        </div>
                    </div>
                    <button type="submit" class="btn-auth-submit">
                        Verify and Sign In <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
            @endif

            <form action="{{ route('login.otp.resend') }}" method="POST" class="auth-form">
                @csrf
                <button type="submit" class="btn-auth-submit">
                    Send a New Code <i class="fas fa-envelope" aria-hidden="true"></i>
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
