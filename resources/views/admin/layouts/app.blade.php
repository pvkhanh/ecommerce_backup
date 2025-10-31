<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #2c3e50;
            color: white;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            background: #1a252f;
            text-align: center;
        }

        .sidebar-menu {
            padding: 10px 0;
        }

        .sidebar-menu-item {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu-item:hover,
        .sidebar-menu-item.active {
            background: #34495e;
            color: white;
            border-left: 3px solid #3498db;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8f9fa;
        }

        .topbar {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-wrapper {
            padding: 20px;
        }

        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
        }

        .image-select-card {
            cursor: pointer;
            transition: all 0.3s;
        }

        .image-select-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">Admin Panel</h4>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}"
                class="sidebar-menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box me-2"></i> Sản phẩm
            </a>
            <a href="{{ route('admin.images.index') }}"
                class="sidebar-menu-item {{ request()->routeIs('admin.images.*') ? 'active' : '' }}">
                <i class="fas fa-images me-2"></i> Quản lý ảnh
            </a>
            <a href="#" class="sidebar-menu-item">
                <i class="fas fa-folder me-2"></i> Danh mục
            </a>
            <a href="#" class="sidebar-menu-item">
                <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
            </a>
            <a href="#" class="sidebar-menu-item">
                <i class="fas fa-users me-2"></i> Khách hàng
            </a>
            <a href="#" class="sidebar-menu-item">
                <i class="fas fa-star me-2"></i> Đánh giá
            </a>
            <a href="#" class="sidebar-menu-item">
                <i class="fas fa-cog me-2"></i> Cài đặt
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div>
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <span class="ms-2">{{ auth()->user()->username ?? 'Admin' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Tài khoản</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Cài đặt</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="#">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Auto-hide alerts
        setTimeout(function() {
            document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>
