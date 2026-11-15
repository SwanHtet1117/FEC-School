<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose a New Password | FEC International School</title>
    <meta name="description" content="Choose a new password for your FEC International School account.">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('partials.header')

    <section class="auth-hero">
        <h1>Choose a New Password</h1>
        <p>Your email code was verified. Set a new password to continue.</p>
    </section>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Set a new password</h2>
                <p>Use at least eight characters. You will need this password the next time you sign in.</p>
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

            <form action="{{ route('password.update') }}" method="POST" class="auth-form">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <input type="password" id="password" name="password" minlength="8" autocomplete="new-password" placeholder="Enter a new password" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" autocomplete="new-password" placeholder="Re-enter the new password" required>
                    </div>
                </div>
                <button type="submit" class="btn-auth-submit">
                    Reset Password <i class="fas fa-check" aria-hidden="true"></i>
                </button>
            </form>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
