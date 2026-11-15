<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage your FEC International School account settings">
    <title>Account Settings | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('partials.header')

    <section class="auth-hero">
        <h1>Account Settings</h1>
        <p>Change your account password</p>
    </section>

    <main class="auth-container account-container">
        <section class="auth-card">
            <div class="auth-header">
                <h2>Update password</h2>
                <p>Enter your current password before choosing a new one.</p>
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

            <form action="{{ route('account.settings.update') }}" method="POST" class="auth-form">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="current_password">Current password</label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password" required>
                </div>
                <div class="form-group">
                    <label for="password">New password</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm new password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                </div>
                <button type="submit" class="btn-auth-submit">
                    Update password <i class="fa-solid fa-lock" aria-hidden="true"></i>
                </button>
            </form>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
