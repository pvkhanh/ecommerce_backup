<div class="sidebar" id="sidebar">
    <div class="sidebar-header text-center p-3">
        <h4 class="text-white mb-4">Admin Panel</h4>
    </div>

    <nav class="sidebar-menu nav flex-column p-2">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu-item nav-link text-light {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home me-2"></i> Dashboard 📊
        </a>

        <!-- Products -->
        <a href="{{ route('admin.products.index') }}"
            class="sidebar-menu-item nav-link text-light {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fas fa-box me-2"></i> Sản phẩm 🛒
        </a>

        <!-- Images -->
        <a href="{{ route('admin.images.index') }}"
            class="sidebar-menu-item nav-link text-light {{ request()->routeIs('admin.images.*') ? 'active' : '' }}">
            <i class="fas fa-images me-2"></i> Quản lý ảnh
        </a>

        <!-- Categories -->
        <a href="{{ route('admin.categories.index') }}"
            class="sidebar-menu-item nav-link text-light {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-folder me-2"></i> Danh mục 📂
        </a>

        <!-- Orders -->
        <a href="{{ route('admin.orders.index') }}"
            class="sidebar-menu-item nav-link text-light {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart me-2"></i> Đơn hàng 📦
        </a>
        <!-- Users -->
        <a href="{{ route('admin.users.index') }}"
            class="sidebar-menu-item nav-link text-light {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users me-2"></i> Khách hàng 👤
        </a>

        <!-- Reviews -->
        <a href="#" class="sidebar-menu-item nav-link text-light">
            <i class="fas fa-star me-2"></i> Đánh giá
        </a>

        <!-- Settings -->
        <a href="#" class="sidebar-menu-item nav-link text-light">
            <i class="fas fa-cog me-2"></i> Cài đặt
        </a>
    </nav>
</div>
