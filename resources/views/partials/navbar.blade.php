<div class="navbar">
    <button class="btn btn-link d-md-none" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
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
                    <form method="POST"
                        action="{#}>
                        @csrf
                        <button type="submit"
                        class="dropdown-item">
                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
