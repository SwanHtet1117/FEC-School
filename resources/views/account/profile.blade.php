<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage your FEC International School account profile">
    <title>My Profile | FEC International School</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('partials.header')

    <section class="auth-hero">
        <h1>My Profile</h1>
        <p>Update your account name and email address</p>
    </section>

    <main class="auth-container account-container">
        <section class="auth-card">
            <div class="auth-header">
                <h2>Profile details</h2>
                <p>Keep your account information up to date.</p>
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

            <form action="{{ route('account.profile.update') }}" method="POST" class="auth-form">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="255" autocomplete="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" autocomplete="email" required>
                </div>
                <button type="submit" class="btn-auth-submit">
                    Save profile <i class="fa-regular fa-floppy-disk" aria-hidden="true"></i>
                </button>
            </form>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
