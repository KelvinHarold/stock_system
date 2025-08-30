<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KiligadgetStore</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
 <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/Logo.png') }}">
 <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



  <style>
    body {
        min-height: 100vh;
        display: flex;
        font-family: 'Segoe UI', sans-serif;
        background-color: #f4f5f7;
    }

    /* Sidebar */
    #sidebar {
        min-width: 220px;
        max-width: 220px;
        background-color: #fff;
        border-right: 1px solid #ddd;
        transition: all 0.3s;
        padding-top: 1.5rem; /* More space at top */
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        position: fixed;
        height: 100%;
        overflow-y: auto;
    }

    #sidebar .nav-link {
        color: #333;
        font-weight: 500;
        padding: 0.75rem 1.25rem; /* Added extra horizontal padding */
        border-radius: 0.5rem; /* Slightly rounder */
        margin: 0.5rem 0; /* More spacing between links */
        display: flex;
        align-items: center;
        gap: 0.75rem; /* More space between icon and text */
        transition: all 0.2s ease-in-out;
    }

    #sidebar .nav-link:hover {
        background-color: #f0f0f0;
        padding-left: 1.5rem; /* Subtle slide effect on hover */
        color: #0d6efd;
    }

    #sidebar .nav-link.active-link {
        color: #0d6efd;
        background-color: #e7f1ff;
    }

    /* Logout button */
    #sidebar form button {
        width: 90%;
        margin: 1rem auto 0;
        display: block;
        padding: 0.5rem 0;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.2s ease-in-out;
    }

    #sidebar form button:hover {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }
    /* Main content */
    #content {
        margin-left: 220px;
        flex: 1;
        padding: 2rem;
    }

    /* Header */
    #header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    #header h1 {
        font-size: 1.75rem;
        font-weight: 600;
    }

    #header .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
    }

    #header .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Font Awesome icons */
    i.fas, i.far, i.fab {
        color: #0d6efd;
    }

    /* Alerts */
    .alert i {
        margin-right: 0.5rem;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="text-center mb-4">
        <a href="" class="d-block mb-2">
            <img src="{{ asset('images/Logo.png') }}" height="80" alt="Logo">
        </a>
        <strong>KiligadgetStore</strong>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active-link' : '' }}" 
               href="{{ route('index.page') }}">
               <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active-link' : '' }}" 
               href="{{ route('products.index') }}">
               <i class="fas fa-box"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active-link' : '' }}" 
               href="{{ route('transactions.index') }}">
               <i class="fas fa-exchange-alt"></i> Transactions
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.stock') ? 'active-link' : '' }}" 
               href="{{ route('reports.stock') }}">
               <i class="fas fa-file-alt"></i> Stock Report
            </a>
        </li>

        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="btn btn-sm btn-outline-danger">Logout</button>
</form>

    </ul>
</nav>

<!-- Main content -->
<div id="content">
    <!-- Page Header -->
    <div id="header">
        <h1>@yield('page-title', 'Business Overview')</h1>
        <div class="user-info" data-bs-toggle="modal" data-bs-target="#profileModal">
            <span>{{ auth()->user()->name ?? 'Admin' }}</span>
            <img src="{{ auth()->user()->profile_photo_url ? asset('storage/' . auth()->user()->profile_photo_url) : asset('images/user-avatar.png') }}" 
                 alt="User Avatar">
        </div>
    </div>

    @include('components.success-message')

    @if($errors->any())
      <div class="alert alert-danger">
          <ul class="mb-0">
              @foreach($errors->all() as $e)
                  <li><i class="fas fa-exclamation-circle"></i> {{ $e }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <!-- Page content -->
    @yield('content')
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('user.update-profile') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel">Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Profile Image -->
            <div class="mb-3 text-center">
                <img src="{{ auth()->user()->profile_photo_url ? asset('storage/' . auth()->user()->profile_photo_url) : asset('images/user-avatar.png') }}" 
                     class="rounded-circle" width="100" height="100" alt="Profile Picture">
                <input type="file" name="profile_photo" class="form-control mt-2">
            </div>

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
            </div>

            <!-- Change Password -->
            <hr>
            <h6>Change Password</h6>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Changes
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
