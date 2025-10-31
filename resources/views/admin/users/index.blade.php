{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1"><i class="fa-solid fa-users me-2"></i> Quản lý người dùng</h3>
            <p class="text-muted mb-0">Danh sách tài khoản hiện có trong hệ thống</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm px-3">
            <i class="fa-solid fa-plus me-1"></i> Thêm người dùng
        </a>
    </div>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control border-primary"
                placeholder="🔍 Tìm kiếm theo tên hoặc email..." value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">Tìm</button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:60px;">#</th>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th class="text-center">Vai trò</th>
                        <th class="text-center">Ngày tạo</th>
                        <th class="text-center" style="width:160px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-center">{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                        alt="Avatar" class="rounded-circle me-2" width="40" height="40">
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $user->username }}</div>
                                        <div class="small text-muted">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-center text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <x-action-buttons :show="route('admin.users.show', $user->id)" :edit="route('admin.users.edit', $user->id)" :delete="route('admin.users.destroy', $user->id)" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fa-regular fa-circle-xmark fs-4 d-block mb-2"></i>
                                Không có người dùng nào được tìm thấy.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links('components.pagination') }}
    </div>
@endsection --}}


{{-- Bản 2
@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-1">
                    <i class="fa-solid fa-users me-2"></i> Quản lý người dùng
                </h3>
                <p class="text-muted mb-0">Danh sách tất cả người dùng trong hệ thống</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm px-3">
                <i class="fa-solid fa-user-plus me-1"></i> Thêm người dùng
            </a>
        </div>

        <!-- Bộ lọc -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control" placeholder="Nhập tên hoặc email..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Vai trò</label>
                            <select name="role" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Người mua</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Vô hiệu hóa</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-filter me-1"></i> Lọc
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Avatar</th>
                                <th>Tên người dùng</th>
                                <th>Điện thoại</th>
                                <th>Vai trò</th>
                                <th class="text-center">Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        @php
                                            $avatar =
                                                optional($user->images()->wherePivot('is_main', true)->first())->path ??
                                                null;
                                        @endphp
                                        @if ($avatar)
                                            <img src="{{ asset('storage/' . $avatar) }}"
                                                class="rounded-circle border shadow-sm"
                                                style="width:45px; height:45px; object-fit:cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm"
                                                style="width:45px; height:45px;">
                                                {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $user->username }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </td>
                                    <td>{{ $user->phone ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-flex justify-content-center">
                                            <input class="form-check-input toggle-status" type="checkbox"
                                                data-id="{{ $user->id }}" {{ $user->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="btn btn-outline-info btn-sm rounded-circle" data-bs-toggle="tooltip"
                                                title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-outline-warning btn-sm rounded-circle"
                                                data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm rounded-circle btn-delete"
                                                data-action="{{ route('admin.users.destroy', $user->id) }}"
                                                data-username="{{ $user->username }}" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fa-regular fa-circle-xmark fs-4 d-block mb-2"></i>
                                        Không có người dùng nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-white py-3">
                {{ $users->links('components.pagination') }}
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Khởi tạo Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

            // ===================================
            // Toggle trạng thái
            // ===================================
            document.querySelectorAll('.toggle-status').forEach(checkbox => {
                checkbox.addEventListener('change', async function() {
                    const userId = this.dataset.id;
                    const isActive = this.checked ? 1 : 0;

                    try {
                        const response = await fetch(`/admin/users/${userId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                is_active: isActive
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message || 'Không thể cập nhật trạng thái!');
                            this.checked = !this.checked;
                        }
                    } catch (error) {
                        toastr.error('❌ Lỗi kết nối!');
                        this.checked = !this.checked;
                    }
                });
            });

            // ===================================
            // Xóa với SweetAlert2 - CÓ LOADING VÀ RELOAD
            // ===================================
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const deleteUrl = this.dataset.action;
                    const username = this.dataset.username;

                    Swal.fire({
                        title: 'Xác nhận xóa vĩnh viễn',
                        html: `
                            <div class="text-center">
                                <i class="fa-solid fa-user-xmark text-danger mb-3" style="font-size: 64px;"></i>
                                <p class="mb-2">Bạn có chắc chắn muốn xóa người dùng</p>
                                <p class="fw-bold text-danger fs-5 mb-2">${username}</p>
                                <div class="alert alert-warning mt-3">
                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                    <small><strong>Cảnh báo:</strong> Hành động này sẽ xóa vĩnh viễn user và TẤT CẢ dữ liệu liên quan (đơn hàng, giỏ hàng, đánh giá, v.v.) khỏi cơ sở dữ liệu!</small>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-solid fa-trash me-2"></i> Xóa vĩnh viễn',
                        cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy bỏ',
                        reverseButtons: true,
                        width: '600px',
                        customClass: {
                            confirmButton: 'btn btn-danger btn-lg px-4',
                            cancelButton: 'btn btn-secondary btn-lg px-4'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Hiển thị loading
                            Swal.fire({
                                title: 'Đang xóa...',
                                html: `
                                    <div class="text-center">
                                        <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mb-0">Vui lòng đợi, đang xóa người dùng <strong>${username}</strong> và tất cả dữ liệu liên quan...</p>
                                    </div>
                                `,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    // Tạo form và submit
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = deleteUrl;

                                    // CSRF Token
                                    const csrfInput = document.createElement(
                                        'input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = '_token';
                                    csrfInput.value = document.querySelector(
                                            'meta[name="csrf-token"]')
                                        .content;
                                    form.appendChild(csrfInput);

                                    // Method DELETE
                                    const methodInput = document.createElement(
                                        'input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'DELETE';
                                    form.appendChild(methodInput);

                                    // Submit form
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>
@endpush --}}


{{-- Bản 3: 23/10/2025 --}}

@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-users-gear text-primary me-2"></i>
                            Quản lý người dùng
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Người dùng</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fa-solid fa-user-plus me-2"></i> Thêm người dùng
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Tổng người dùng</h6>
                                <h3 class="fw-bold mb-0">{{ \App\Models\User::count() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Đang hoạt động</h6>
                                <h3 class="fw-bold mb-0">{{ \App\Models\User::where('is_active', 1)->count() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Đã xác thực email</h6>
                                <h3 class="fw-bold mb-0">{{ \App\Models\User::whereNotNull('email_verified_at')->count() }}
                                </h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa-solid fa-envelope-circle-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Quản trị viên</h6>
                                <h3 class="fw-bold mb-0">{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-filter text-primary me-2"></i>Bộ lọc
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-magnifying-glass text-muted me-1"></i> Tìm kiếm
                            </label>
                            <input type="text" name="search" class="form-control form-control-lg"
                                placeholder="Tên hoặc email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-user-tag text-muted me-1"></i> Vai trò
                            </label>
                            <select name="role" class="form-select form-select-lg">
                                <option value="">Tất cả</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Người mua</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-toggle-on text-muted me-1"></i> Trạng thái
                            </label>
                            <select name="status" class="form-select form-select-lg">
                                <option value="">Tất cả</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Vô hiệu hóa
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-envelope-circle-check text-muted me-1"></i> Email
                            </label>
                            <select name="verified" class="form-select form-select-lg">
                                <option value="">Tất cả</option>
                                <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Đã xác thực
                                </option>
                                <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Chưa xác thực
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-fill">
                                <i class="fa-solid fa-filter me-2"></i> Lọc
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fa-solid fa-rotate-right"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-solid fa-list text-primary me-2"></i>Danh sách người dùng
                        <span class="badge bg-primary fs-6">{{ $users->total() }} người dùng</span>
                    </h5>
                    <a href="{{ route('admin.users.trashed') }}" class="btn btn-secondary btn-lg shadow-sm">
                        <i class="fa-solid fa-trash-arrow-up me-2"></i> Thùng rác
                    </a>


                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-center" style="width:80px;">#</th>
                                <th class="px-4 py-3" style="width:300px;">Người dùng</th>
                                <th class="px-4 py-3">Thông tin liên hệ</th>
                                <th class="px-4 py-3 text-center">Vai trò</th>
                                <th class="px-4 py-3 text-center">Trạng thái</th>
                                <th class="px-4 py-3 text-center">Email</th>
                                {{-- <th class="px-4 py-3 text-center">Token</th> --}}
                                <th class="px-4 py-3">Ngày tạo</th>
                                <th class="px-4 py-3 text-center" style="width:180px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="border-bottom">
                                    <td class="text-center px-4">
                                        <span class="badge bg-light text-dark fs-6">{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            {{-- @php
                                                $avatar =
                                                    optional($user->images()->wherePivot('is_main', true)->first())
                                                        ->path ?? null;
                                            @endphp
                                            @if ($avatar)
                                                <img src="{{ asset('storage/' . $avatar) }}"
                                                    class="rounded-circle border border-3 border-primary shadow-sm me-3"
                                                    style="width:50px; height:50px; object-fit:cover;">
                                            @else
                                                <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center shadow-sm me-3 fw-bold"
                                                    style="width:50px; height:50px; font-size:20px;">
                                                    {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif --}}
                                            @php
                                                $avatar = optional(
                                                    $user->images()->wherePivot('is_main', true)->first(),
                                                )->path;
                                                $avatarUrl = $avatar
                                                    ? asset('storage/' . $avatar) // avatar đã upload
                                                    : asset('images/default-user.png'); // ảnh mặc định trong public/images/
                                            @endphp

                                            <img src="{{ $avatarUrl }}"
                                                class="rounded-circle border border-3 border-primary shadow-sm me-3"
                                                style="width:50px; height:50px; object-fit:cover;">

                                            <div>
                                                <div class="fw-bold text-dark mb-1">{{ $user->username }}</div>
                                                <div class="small text-muted">
                                                    <i class="fa-solid fa-envelope me-1"></i>{{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <div class="small">
                                            @if ($user->phone)
                                                <div class="mb-1">
                                                    <i class="fa-solid fa-phone text-primary me-1"></i>
                                                    <span class="fw-semibold">{{ $user->phone }}</span>
                                                </div>
                                            @endif
                                            @if ($user->gender)
                                                <div>
                                                    <i class="fa-solid fa-venus-mars text-info me-1"></i>
                                                    <span class="text-capitalize">{{ $user->gender }}</span>
                                                </div>
                                            @endif
                                            @if (!$user->phone && !$user->gender)
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center px-4">
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-danger fs-6 px-3 py-2">
                                                <i class="fa-solid fa-shield-halved me-1"></i>Admin
                                            </span>
                                        @else
                                            <span class="badge bg-info fs-6 px-3 py-2">
                                                <i class="fa-solid fa-user me-1"></i>Buyer
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input toggle-status" type="checkbox" role="switch"
                                                style="font-size:1.5rem; cursor:pointer;" data-id="{{ $user->id }}"
                                                {{ $user->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center px-4">
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success fs-6 px-3 py-2" data-bs-toggle="tooltip"
                                                title="Xác thực lúc: {{ $user->email_verified_at->format('d/m/Y H:i') }}">
                                                <i class="fa-solid fa-circle-check me-1"></i>Đã xác thực
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                                <i class="fa-solid fa-clock me-1"></i>Chưa xác thực
                                            </span>
                                        @endif
                                    </td>
                                    {{-- <td class="text-center px-4">
                                        @if ($user->remember_token)
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                title="Token: {{ Str::limit($user->remember_token, 20) }}">
                                                <i class="fa-solid fa-key me-1"></i>Có token
                                            </button>
                                        @else
                                            <span class="badge bg-light text-muted fs-6">
                                                <i class="fa-solid fa-ban me-1"></i>Không có
                                            </span>
                                        @endif
                                    </td> --}}
                                    <td class="px-4">
                                        <div class="small">
                                            <div class="fw-semibold text-dark">{{ $user->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-muted">{{ $user->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip"
                                                title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip"
                                                title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            {{-- Xoá vĩnh viễn --}}
                                            {{-- <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-action="{{ route('admin.users.destroy', $user->id) }}"
                                                data-username="{{ $user->username }}" data-bs-toggle="tooltip"
                                                title="Xóa vĩnh viễn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button> --}}


                                            {{-- Xoá mềm --}}
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-action="{{ route('admin.users.destroy', $user->id) }}"
                                                data-username="{{ $user->username }}" data-bs-toggle="tooltip"
                                                title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            <h5>Không có người dùng nào</h5>
                                            <p class="mb-0">Thử thay đổi bộ lọc hoặc thêm người dùng mới</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }} trong {{ $users->total() }}
                            người dùng
                        </div>
                        <div>
                            {{ $users->links('components.pagination') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-group .btn {
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }
    </style>
@endsection

@push('scripts')
    {{-- Xoá mềm --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Khởi tạo Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

            // Toggle trạng thái
            document.querySelectorAll('.toggle-status').forEach(checkbox => {
                checkbox.addEventListener('change', async function() {
                    const userId = this.dataset.id;
                    const isActive = this.checked ? 1 : 0;

                    try {
                        const response = await fetch(`/admin/users/${userId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                is_active: isActive
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message || 'Không thể cập nhật trạng thái!');
                            this.checked = !this.checked;
                        }
                    } catch (error) {
                        toastr.error('❌ Lỗi kết nối!');
                        this.checked = !this.checked;
                    }
                });
            });

            // Xóa mềm với SweetAlert2
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const deleteUrl = this.dataset.action;
                    const username = this.dataset.username;

                    Swal.fire({
                        title: 'Xác nhận xóa',
                        html: `
                    <div class="text-center">
                        <i class="fa-solid fa-user-xmark text-warning mb-3" style="font-size: 64px;"></i>
                        <p class="mb-2">Bạn có chắc chắn muốn xóa người dùng</p>
                        <p class="fw-bold text-warning fs-5 mb-2">${username}</p>
                        <div class="alert alert-info mt-3">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <small>Người dùng sẽ được chuyển vào thùng rác, có thể khôi phục sau.</small>
                        </div>
                    </div>
                `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#ffc107',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-solid fa-trash me-2"></i> Xóa',
                        cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy bỏ',
                        reverseButtons: true,
                        width: '600px',
                        customClass: {
                            confirmButton: 'btn btn-warning btn-lg px-4',
                            cancelButton: 'btn btn-secondary btn-lg px-4'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Đang xóa...',
                                html: `
                            <div class="text-center">
                                <div class="spinner-border text-warning mb-3" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mb-0">Vui lòng đợi...</p>
                            </div>
                        `,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = deleteUrl;

                                    const csrfInput = document.createElement(
                                        'input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = '_token';
                                    csrfInput.value = document.querySelector(
                                        'meta[name="csrf-token"]').content;
                                    form.appendChild(csrfInput);

                                    const methodInput = document.createElement(
                                        'input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'DELETE';
                                    form.appendChild(methodInput);

                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>


    {{-- Xoá vĩnh viễn --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Khởi tạo Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

            // Toggle trạng thái
            document.querySelectorAll('.toggle-status').forEach(checkbox => {
                checkbox.addEventListener('change', async function() {
                    const userId = this.dataset.id;
                    const isActive = this.checked ? 1 : 0;

                    try {
                        const response = await fetch(`/admin/users/${userId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                is_active: isActive
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message || 'Không thể cập nhật trạng thái!');
                            this.checked = !this.checked;
                        }
                    } catch (error) {
                        toastr.error('❌ Lỗi kết nối!');
                        this.checked = !this.checked;
                    }
                });
            });

            // Xóa với SweetAlert2
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const deleteUrl = this.dataset.action;
                    const username = this.dataset.username;

                    Swal.fire({
                        title: 'Xác nhận xóa vĩnh viễn',
                        html: `
                            <div class="text-center">
                                <i class="fa-solid fa-user-xmark text-danger mb-3" style="font-size: 64px;"></i>
                                <p class="mb-2">Bạn có chắc chắn muốn xóa người dùng</p>
                                <p class="fw-bold text-danger fs-5 mb-2">${username}</p>
                                <div class="alert alert-warning mt-3">
                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                    <small><strong>Cảnh báo:</strong> Hành động này sẽ xóa vĩnh viễn user và TẤT CẢ dữ liệu liên quan!</small>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-solid fa-trash me-2"></i> Xóa vĩnh viễn',
                        cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy bỏ',
                        reverseButtons: true,
                        width: '600px',
                        customClass: {
                            confirmButton: 'btn btn-danger btn-lg px-4',
                            cancelButton: 'btn btn-secondary btn-lg px-4'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Đang xóa...',
                                html: `
                                    <div class="text-center">
                                        <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mb-0">Vui lòng đợi...</p>
                                    </div>
                                `,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = deleteUrl;

                                    const csrfInput = document.createElement(
                                        'input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = '_token';
                                    csrfInput.value = document.querySelector(
                                        'meta[name="csrf-token"]').content;
                                    form.appendChild(csrfInput);

                                    const methodInput = document.createElement(
                                        'input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'DELETE';
                                    form.appendChild(methodInput);

                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        }
                    });
                });
            });
        });
    </script> --}}
@endpush
