<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - KiligadgetStore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    body {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f4f5f7;
        font-family: 'Segoe UI', sans-serif;
    }
    .login-card {
        background: #fff;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
    }
    .login-card h2 {
        margin-bottom: 1.5rem;
        text-align: center;
    }
  </style>
</head>
<body>

<div class="login-card">
    <h2 class="d-flex align-items-center justify-content-center gap-2">
        <img src="{{ asset('images/Logo.jpg') }}" alt="Logo" class="rounded" width="90" height="90">
        <div>KiligadgetStore</div>
    </h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-user-plus"></i> Register
        </button>
        <div class="mt-3 text-center">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
