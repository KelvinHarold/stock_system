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

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
        min-height: 100vh;
        display: flex;
        font-family: 'Segoe UI', sans-serif;
        background-color: #f9f9fb;
        margin: 0;
        flex-direction: column;
    }

    /* Sidebar */
    #sidebar {
        width: 240px;
        background-color: #fff;
        border-right: 1px solid #e0e0e0;
        box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        padding: 1.5rem 1rem;
        position: fixed;
        height: 100%;
        overflow-y: auto;
        z-index: 1000;
        transition: transform 0.3s ease-in-out;
    }

    /* Mobile sidebar toggle button */
    #sidebarToggle {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1100;
        background: #0d6efd;
        color: white;
        border: none;
        border-radius: 4px;
        width: 40px;
        height: 40px;
        display: none;
        align-items: center;
        justify-content: center;
    }

    /* Main content */
    #content {
        margin-left: 240px;
        width: calc(100% - 240px);
        padding: 2rem 2.5rem;
        transition: all 0.3s ease;
    }

    #sidebar .logo {
        text-align: center;
        margin-bottom: 2rem;
    }

    #sidebar .logo img {
        height: 70px;
    }

    #sidebar .logo strong {
        display: block;
        font-size: 1.2rem;
        margin-top: 0.5rem;
        color: #28a745;
    }

    #sidebar .nav-link {
        color: #555;
        font-weight: 500;
        padding: 0.75rem 1.2rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.2s ease-in-out;
    }

    #sidebar .nav-link:hover {
        background-color: #f0f4ff;
        color: #0d6efd;
        padding-left: 1.5rem;
    }

    #sidebar .nav-link.active-link {
        background-color: #e6f0ff;
        color: #0d6efd;
    }

    #sidebar form button {
        width: 100%;
        margin-top: 1rem;
        padding: 0.5rem 0;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }

    #sidebar form button:hover {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
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
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    #header .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        background-color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }

    #header .user-info:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    #header .user-info img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #0d6efd;
    }

    /* Font Awesome icons */
    i.fas, i.far, i.fab {
        color: #0d6efd;
    }

    /* Alerts */
    .alert i {
        margin-right: 0.5rem;
    }

    /* Scrollbar for sidebar */
    #sidebar::-webkit-scrollbar {
        width: 6px;
    }

    #sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.1);
        border-radius: 3px;
    }
    
    .logo-img {
        width: 70px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #28a745;
    }

    .store-name {
        font-size: 1.2rem;
        color: #28a745;
        font-weight: bold;
        letter-spacing: 1px;
        text-decoration: none;
    }

    /* Overlay for mobile when sidebar is open */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    /* Responsive styles */
    @media (max-width: 992px) {
        #sidebar {
            transform: translateX(-100%);
            width: 220px;
        }
        
        #sidebar.active {
            transform: translateX(0);
        }
        
        #sidebarToggle {
            display: flex;
        }
        
        #content {
            margin-left: 0;
            width: 100%;
            padding: 1.5rem;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
    }

    @media (max-width: 768px) {
        #header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        #header .user-info {
            align-self: flex-end;
        }
        
        #content {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        #header h1 {
            font-size: 1.5rem;
        }
        
        #header .user-info span {
            display: none;
        }
        
        .logo-img {
            width: 50px;
            height: 40px;
        }
        
        .store-name {
            font-size: 1rem;
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
        <img src="{{ asset('images/Logo.jpg') }}" alt="Logo" class="logo-img">
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
            <a class="nav-link {{ request()->routeIs('sales.*') ? 'active-link' : '' }}" 
               href="{{ route('sales.create') }}">
               <i class="fas fa-shopping-cart"></i> Stock Out
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active-link' : '' }}" 
               href="{{ route('purchases.create') }}">
               <i class="fas fa-cart-plus"></i> Stock In
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
            <button class="btn btn-sm btn-outline-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
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
            <div class="mb-3 text-center">
                <img src="{{ auth()->user()->profile_photo_url ? asset('storage/' . auth()->user()->profile_photo_url) : asset('images/user-avatar.png') }}" 
                     class="rounded-circle" width="100" height="100" alt="Profile Picture">
                <input type="file" name="profile_photo" class="form-control mt-2">
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

<script>
    // Toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const content = document.getElementById('content');
        
        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }
        
        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
        
        // Close sidebar when a nav link is clicked on mobile
        if (window.innerWidth < 992) {
            const navLinks = document.querySelectorAll('#sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            });
        }
        
        // Adjust content padding based on screen size
        function adjustContentPadding() {
            if (window.innerWidth < 576) {
                content.style.padding = '0.75rem';
            } else if (window.innerWidth < 768) {
                content.style.padding = '1rem';
            } else {
                content.style.padding = '2rem 2.5rem';
            }
        }
        
        // Initial call
        adjustContentPadding();
        
        // Listen for window resize
        window.addEventListener('resize', adjustContentPadding);
    });
</script>

</body>
</html>