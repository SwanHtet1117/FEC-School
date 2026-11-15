<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | FEC International School</title>
    <meta name="description" content="Verify your FEC International School account email address.">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
</head>
<body>
    @include('partials.header')

    <section class="auth-hero fade-in-up">
        <h1>Verify Your Email</h1>
        <p>Confirm your email address to continue with registration.</p>
    </section>

    <div class="auth-container fade-in-up">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Check Your Inbox</h2>
                <p>Use the secure link we emailed you. Email verification is required before sign-in; admin approval is also required for new accounts.</p>
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

            <form action="{{ route('verification.send') }}" method="POST" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $email) }}"
                            autocomplete="email"
                            maxlength="255"
                            placeholder="Enter your email"
                            required
                        >
                    </div>
                </div>
                <button type="submit" class="btn-auth-submit">
                    Resend Verification Link <i class="fas fa-arrow-right" aria-hidden="true"></i>
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
