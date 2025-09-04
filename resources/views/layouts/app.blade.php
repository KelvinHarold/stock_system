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
  <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/Logo.jpg') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root {
        --primary-color: #00796b;
        --secondary-color: #004d40;
        --background-color: #f0f2f5;
        --sidebar-bg: #ffffff;
        --text-color: #333;
        --text-muted: #6c757d;
        --border-color: #dee2e6;
        --card-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
      
    body {
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
        background-color: var(--background-color);
        margin: 0;
    }

    /* Sidebar */
    #sidebar {
        width: 250px;
        background-color: var(--sidebar-bg);
        border-right: 1px solid var(--border-color);
        padding: 1.5rem 1rem;
        position: fixed;
        height: 100%;
        overflow-y: auto;
        z-index: 1000;
        transition: transform 0.3s ease-in-out;
        display: flex;
        flex-direction: column;
    }

    /* Mobile sidebar toggle button */
    #sidebarToggle {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1100;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        width: 45px;
        height: 45px;
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
      
    /* Main content */
    #main-content {
        margin-left: 250px;
        padding: 2rem;
        transition: margin-left 0.3s ease;
    }
      
    #sidebar .logo {
        text-align: center;
        margin-bottom: 2.5rem;
        padding: 0 1rem;
    }

    #sidebar .logo img {
        height: 60px;
        width: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
        padding: 3px;
    }

    #sidebar .logo strong {
        display: block;
        font-size: 1.1rem;
        margin-top: 0.75rem;
        color: var(--secondary-color);
        font-weight: 600;
    }

    #sidebar .nav {
        flex-grow: 1;
    }

    #sidebar .nav-link {
        color: var(--text-muted);
        font-weight: 500;
        padding: 0.8rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease-in-out;
    }

    #sidebar .nav-link:hover {
        background-color: #e8f5e9;
        color: var(--primary-color);
    }
      
    #sidebar .nav-link.active-link {
        background-color: var(--primary-color);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 121, 107, 0.4);
    }

    #sidebar .nav-link i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
      
    #sidebar .logout-form {
        margin-top: auto;
        padding: 0 1rem;
    }

    #sidebar .logout-form button {
        width: 100%;
        margin-top: 1rem;
        padding: 0.75rem 0;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.2s;
        background-color: #fce4ec;
        color: #c2185b;
        border: 1px solid #f8bbd0;
    }
      
    #sidebar .logout-form button:hover {
        background-color: #c2185b;
        color: #fff;
    }

    /* Header */
    #header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    #header h1 {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--text-color);
        margin: 0;
    }

    #header .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        background-color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        box-shadow: var(--card-shadow);
        transition: all 0.2s;
    }

    #header .user-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
      
    #header .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    #header .user-info span {
        font-weight: 500;
        color: var(--text-color);
    }

    /* Content Wrapper */
    .content-wrapper {
        background-color: #fff;
        padding: 2rem;
        border-radius: 0.75rem;
        box-shadow: var(--card-shadow);
    }

    /* Summary Cards */
    .summary-card {
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .summary-card .icon-circle {
        height: 3rem;
        width: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .summary-card .icon-circle i {
        font-size: 1.5rem;
    }

    /* Soft Background Colors */
    .bg-primary-soft { background-color: rgba(78, 115, 223, 0.1); }
    .bg-info-soft { background-color: rgba(54, 185, 204, 0.1); }
    .bg-warning-soft { background-color: rgba(246, 194, 62, 0.1); }
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1); }

    /* Text Colors */
    .text-primary { color: #4e73df !important; }
    .text-info { color: #36b9cc !important; }
    .text-warning { color: #f6c23e !important; }
    .text-success { color: #1cc88a !important; }

    /* Alerts */
    .alert {
        border-radius: 0.5rem;
    }

    .alert i {
        margin-right: 0.75rem;
    }
      
    /* Scrollbar for sidebar */
    #sidebar::-webkit-scrollbar {
        width: 5px;
    }

    #sidebar::-webkit-scrollbar-thumb {
        background-color: #dcdcdc;
        border-radius: 3px;
    }
      
    /* Overlay for mobile when sidebar is open */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 999;
    }
      
    /* Modal styles */
    .modal-content {
        border-radius: 0.75rem;
        border: none;
    }
    
    .modal-header {
        background-color: var(--primary-color);
        color: #fff;
        border-bottom: none;
    }
      
    .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Responsive styles */
    @media (max-width: 992px) {
        #sidebar {
            transform: translateX(-100%);
        }
        
        #sidebar.active {
            transform: translateX(0);
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        #sidebarToggle {
            display: flex;
        }
        
        #main-content {
            margin-left: 0;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
    }

    @media (max-width: 768px) {
        #main-content, .content-wrapper {
            padding: 1.5rem;
        }
        
        #header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        #header .user-info {
            align-self: flex-end;
        }
    }

    @media (max-width: 576px) {
        #main-content, .content-wrapper {
            padding: 1rem;
        }

        #header h1 {
            font-size: 1.5rem;
        }
        
        #header .user-info span {
            display: none;
        }
    }
  </style>
</head>
<body>

<!-- Sidebar Toggle Button for Mobile -->
<button id="sidebarToggle" class="d-lg-none">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="logo">
        <img src="{{ asset('images/Logo.jpg') }}" alt="Logo">
        <strong class="store-name ms-2">LEKEI AGROVETS</strong>
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('index.page') ? 'active-link' : '' }}" 
               href="{{ route('index.page') }}">
               <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active-link' : '' }}" 
               href="{{ route('products.index') }}">
               <i class="fas fa-box-open"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active-link' : '' }}" 
               href="{{ route('transactions.index') }}">
               <i class="fas fa-exchange-alt"></i> Transactions
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('sales.*') ? 'active-link' : '' }}" 
               href="{{ route('sales.create') }}">
               <i class="fas fa-shopping-cart"></i> New Sale
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active-link' : '' }}" 
               href="{{ route('purchases.create') }}">
               <i class="fas fa-cart-plus"></i> New Purchase
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.stock') ? 'active-link' : '' }}" 
               href="{{ route('reports.stock') }}">
               <i class="fas fa-chart-bar"></i> Stock Report
            </a>
        </li>
    </ul>

    <div class="logout-form">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn w-100">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</nav>

<!-- Main content -->
<div id="main-content">
    <!-- Page Header -->
    <div id="header">
        <h1>@yield('page-title', 'Business Overview')</h1>
        <div class="user-info" data-bs-toggle="modal" data-bs-target="#profileModal">
            <img src="{{ auth()->user()->profile_photo_url ? asset('storage/' . auth()->user()->profile_photo_url) : asset('images/user-avatar.png') }}" 
                 alt="User Avatar">
            <span>{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
    </div>

    @include('components.success-message')

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
          <ul class="mb-0">
              @foreach($errors->all() as $e)
                  <li><i class="fas fa-exclamation-circle"></i> {{ $e }}</li>
              @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('user.update-profile') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel"><i class="fas fa-user-edit me-2"></i>Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 text-center">
                <img src="{{ auth()->user()->profile_photo_url ? asset('storage/' . auth()->user()->profile_photo_url) : asset('images/user-avatar.png') }}" 
                     class="rounded-circle" width="100" height="100" alt="Profile Picture" style="object-fit: cover;">
                <label for="profile_photo" class="form-label mt-2">Change Profile Photo</label>
                <input type="file" id="profile_photo" name="profile_photo" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
            </div>

            <hr>
            <h6>Change Password (optional)</h6>
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
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
        
        // Close sidebar when a nav link is clicked on mobile
        if (window.innerWidth < 992) {
            const navLinks = document.querySelectorAll('#sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (sidebar.classList.contains('active')) {
                        toggleSidebar();
                    }
                });
            });
        }
    });
</script>

</body>
</html>