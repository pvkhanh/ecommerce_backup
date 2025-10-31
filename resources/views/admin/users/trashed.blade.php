{{-- @extends('layouts.admin')
@section('title', 'Thùng rác người dùng')
@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-danger mb-0">
                    <i class="fa-solid fa-trash text-danger me-2"></i> Thùng rác người dùng
                </h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm" id="btn-restore-all" {{ $users->total() === 0 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-arrow-rotate-left me-1"></i> Khôi phục tất cả
                    </button>
                    <button class="btn btn-danger btn-sm" id="btn-delete-selected" disabled>
                        <i class="fa-solid fa-trash me-1"></i> Xóa đã chọn
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-center">
                                    <input type="checkbox" id="check-all">
                                </th>
                                <th class="px-4 py-3">Người dùng</th>
                                <th class="px-4 py-3 text-center">Vai trò</th>
                                <th class="px-4 py-3 text-center">Email</th>
                                <th class="px-4 py-3 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="users-table">
                            @forelse($users as $user)
                                <tr id="user-row-{{ $user->id }}" class="border-bottom">
                                    <td class="text-center px-4">
                                        <input type="checkbox" class="check-item" value="{{ $user->id }}">
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-bold">{{ $user->username }}</div>
                                        <div class="text-muted">{{ $user->email }}</div>
                                    </td>
                                    <td class="text-center px-4">{{ ucfirst($user->role) }}</td>
                                    <td class="text-center px-4">
                                        {{ $user->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}</td>
                                    <td class="text-center px-4">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-success btn-sm btn-restore" data-id="{{ $user->id }}"
                                                title="Khôi phục">
                                                <i class="fa-solid fa-arrow-rotate-left"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-force-delete"
                                                data-id="{{ $user->id }}" data-username="{{ $user->username }}"
                                                title="Xóa vĩnh viễn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-users">
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            <h5>Không có người dùng nào trong thùng rác</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($users->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }} trong
                            {{ $users->total() }}</div>
                        <div>{{ $users->links('components.pagination') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const checkAll = document.getElementById('check-all');
                const checkItems = () => document.querySelectorAll('.check-item');
                const btnDeleteSelected = document.getElementById('btn-delete-selected');

                // Checkbox select
                checkAll.addEventListener('change', () => {
                    checkItems().forEach(ch => ch.checked = checkAll.checked);
                    btnDeleteSelected.disabled = ![...checkItems()].some(ch => ch.checked);
                });
                document.addEventListener('change', e => {
                    if (e.target.classList.contains('check-item')) {
                        btnDeleteSelected.disabled = ![...checkItems()].some(ch => ch.checked);
                    }
                });

                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Restore single user
                document.addEventListener('click', e => {
                    if (e.target.closest('.btn-restore')) {
                        const btn = e.target.closest('.btn-restore');
                        const id = btn.dataset.id;
                        Swal.fire({
                            title: 'Khôi phục người dùng?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Khôi phục'
                        }).then(result => {
                            if (result.isConfirmed) {
                                fetch(`/admin/users/restore/${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrf
                                    }
                                }).then(r => r.json()).then(res => {
                                    document.getElementById(`user-row-${id}`).remove();
                                    Swal.fire({
                                        icon: 'success',
                                        title: res.message
                                    });
                                    if (!document.querySelectorAll('.check-item').length) {
                                        document.getElementById('no-users').style.display =
                                            'table-row';
                                    }
                                });
                            }
                        });
                    }
                });

                // Restore all
                document.getElementById('btn-restore-all').addEventListener('click', () => {
                    Swal.fire({
                        title: 'Khôi phục tất cả người dùng?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Khôi phục tất cả'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(`/admin/users/restore-all`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf
                                }
                            }).then(r => r.json()).then(res => {
                                document.getElementById('users-table').innerHTML = `
                        <tr id="no-users">
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                                    <h5>Không có người dùng nào trong thùng rác</h5>
                                </div>
                            </td>
                        </tr>`;
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            });
                        }
                    });
                });

                // Force delete single
                document.addEventListener('click', e => {
                    if (e.target.closest('.btn-force-delete')) {
                        const btn = e.target.closest('.btn-force-delete');
                        const id = btn.dataset.id;
                        const username = btn.dataset.username;
                        Swal.fire({
                            title: `Xóa vĩnh viễn người dùng ${username}?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Xóa'
                        }).then(result => {
                            if (result.isConfirmed) {
                                fetch(`/admin/users/force-delete/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': csrf
                                    }
                                }).then(r => r.json()).then(res => {
                                    document.getElementById(`user-row-${id}`).remove();
                                    Swal.fire({
                                        icon: 'success',
                                        title: res.message
                                    });
                                    if (!document.querySelectorAll('.check-item').length) {
                                        document.getElementById('no-users').style.display =
                                            'table-row';
                                    }
                                });
                            }
                        });
                    }
                });

                // Force delete selected
                btnDeleteSelected.addEventListener('click', () => {
                    const ids = [...checkItems()].filter(ch => ch.checked).map(ch => ch.value);
                    if (!ids.length) return;
                    Swal.fire({
                        title: 'Xóa vĩnh viễn các người dùng đã chọn?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Xóa'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(`/admin/users/force-delete-selected`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    ids
                                })
                            }).then(r => r.json()).then(res => {
                                ids.forEach(id => {
                                    const row = document.getElementById(
                                        `user-row-${id}`);
                                    if (row) row.remove();
                                });
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                                if (!document.querySelectorAll('.check-item').length) {
                                    document.getElementById('no-users').style.display =
                                        'table-row';
                                }
                            });
                        }
                    });
                });

            });
        </script>
    @endpush
@endsection --}}




{{-- Bản 2 --}}
{{-- @extends('layouts.admin')
@section('title', 'Thùng rác người dùng')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-danger mb-2">
                        <i class="fa-solid fa-trash text-danger me-2"></i> Thùng rác người dùng
                    </h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                            <li class="breadcrumb-item active">Thùng rác</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Tổng người dùng trong thùng rác</h6>
                                <h3 class="fw-bold mb-0">{{ $users->total() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa-solid fa-trash"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Người dùng admin</h6>
                                <h3 class="fw-bold mb-0">{{ $users->where('role', 'admin')->count() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa-solid fa-user-shield"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Người dùng buyer</h6>
                                <h3 class="fw-bold mb-0">{{ $users->where('role', 'buyer')->count() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa-solid fa-user"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-3">
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-success btn-sm" id="btn-restore-all" {{ $users->total() === 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-arrow-rotate-left me-1"></i> Khôi phục tất cả
                </button>
                <button class="btn btn-danger btn-sm" id="btn-delete-selected" disabled>
                    <i class="fa-solid fa-trash me-1"></i> Xóa đã chọn
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-center" style="width:50px;">
                                    <input type="checkbox" id="check-all">
                                </th>
                                <th class="px-4 py-3">Người dùng</th>
                                <th class="px-4 py-3 text-center">Vai trò</th>
                                <th class="px-4 py-3 text-center">Email</th>
                                <th class="px-4 py-3 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="users-table">
                            @forelse($users as $user)
                                <tr id="user-row-{{ $user->id }}" class="border-bottom">
                                    <td class="text-center px-4">
                                        <input type="checkbox" class="check-item" value="{{ $user->id }}">
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-bold">{{ $user->username }}</div>
                                        <div class="text-muted">{{ $user->email }}</div>
                                    </td>
                                    <td class="text-center px-4">{{ ucfirst($user->role) }}</td>
                                    <td class="text-center px-4">
                                        {{ $user->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-success btn-sm btn-restore"
                                                data-id="{{ $user->id }}" title="Khôi phục">
                                                <i class="fa-solid fa-arrow-rotate-left"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-force-delete"
                                                data-id="{{ $user->id }}" data-username="{{ $user->username }}"
                                                title="Xóa vĩnh viễn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-users">
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            <h5>Không có người dùng nào trong thùng rác</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($users->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }} trong
                            {{ $users->total() }}</div>
                        <div>{{ $users->links('components.pagination') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Script restore / delete tương tự như code bạn đã có
        </script>
    @endpush

    <style>
        .bg-gradient-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%) !important;
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
    </style>
@endsection --}}



{{-- Bản 3 --}}
{{-- @extends('layouts.admin')
@section('title', 'Thùng rác người dùng')

@section('content')
    <div class="container-fluid px-4">

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-danger mb-2">
                        <i class="fa-solid fa-trash text-danger me-2"></i> Thùng rác người dùng
                    </h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                            <li class="breadcrumb-item active">Thùng rác</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Tổng người dùng trong thùng rác</h6>
                                <h3 class="fw-bold mb-0">{{ $users->total() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa-solid fa-trash"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Người dùng admin</h6>
                                <h3 class="fw-bold mb-0">{{ $users->where('role', 'admin')->count() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa-solid fa-user-shield"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Người dùng buyer</h6>
                                <h3 class="fw-bold mb-0">{{ $users->where('role', 'buyer')->count() }}</h3>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa-solid fa-user"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-3">
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-success btn-sm" id="btn-restore-all" {{ $users->total() === 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-arrow-rotate-left me-1"></i> Khôi phục tất cả
                </button>
                <button class="btn btn-danger btn-sm" id="btn-delete-selected" disabled>
                    <i class="fa-solid fa-trash me-1"></i> Xóa đã chọn
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-center" style="width:50px;">
                                    <input type="checkbox" id="check-all">
                                </th>
                                <th class="px-4 py-3">Người dùng</th>
                                <th class="px-4 py-3 text-center">Vai trò</th>
                                <th class="px-4 py-3 text-center">Email</th>
                                <th class="px-4 py-3 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="users-table">
                            @forelse($users as $user)
                                <tr id="user-row-{{ $user->id }}" class="border-bottom">
                                    <td class="text-center px-4">
                                        <input type="checkbox" class="check-item" value="{{ $user->id }}">
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-bold">{{ $user->username }}</div>
                                        <div class="text-muted">{{ $user->email }}</div>
                                    </td>
                                    <td class="text-center px-4">{{ ucfirst($user->role) }}</td>
                                    <td class="text-center px-4">
                                        {{ $user->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-success btn-sm btn-restore"
                                                data-id="{{ $user->id }}" title="Khôi phục">
                                                <i class="fa-solid fa-arrow-rotate-left"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-force-delete"
                                                data-id="{{ $user->id }}" data-username="{{ $user->username }}"
                                                title="Xóa vĩnh viễn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-users">
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            <h5>Không có người dùng nào trong thùng rác</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($users->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }} trong
                            {{ $users->total() }}</div>
                        <div>{{ $users->links('components.pagination') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const checkAll = document.getElementById('check-all');
                const checkItems = () => document.querySelectorAll('.check-item');
                const btnDeleteSelected = document.getElementById('btn-delete-selected');

                // Checkbox select
                checkAll.addEventListener('change', () => {
                    checkItems().forEach(ch => ch.checked = checkAll.checked);
                    btnDeleteSelected.disabled = ![...checkItems()].some(ch => ch.checked);
                });
                document.addEventListener('change', e => {
                    if (e.target.classList.contains('check-item')) {
                        btnDeleteSelected.disabled = ![...checkItems()].some(ch => ch.checked);
                    }
                });

                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Restore single user
                document.addEventListener('click', e => {
                    if (e.target.closest('.btn-restore')) {
                        const btn = e.target.closest('.btn-restore');
                        const id = btn.dataset.id;
                        Swal.fire({
                            title: 'Khôi phục người dùng?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Khôi phục'
                        }).then(result => {
                            if (result.isConfirmed) {
                                fetch(`/admin/users/restore/${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrf
                                    }
                                }).then(r => r.json()).then(res => {
                                    document.getElementById(`user-row-${id}`).remove();
                                    Swal.fire({
                                        icon: 'success',
                                        title: res.message
                                    });
                                    if (!document.querySelectorAll('.check-item').length) {
                                        document.getElementById('no-users').style.display =
                                            'table-row';
                                    }
                                });
                            }
                        });
                    }
                });

                // Restore all
                document.getElementById('btn-restore-all').addEventListener('click', () => {
                    Swal.fire({
                        title: 'Khôi phục tất cả người dùng?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Khôi phục tất cả'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(`/admin/users/restore-all`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf
                                }
                            }).then(r => r.json()).then(res => {
                                document.getElementById('users-table').innerHTML = `
                        <tr id="no-users">
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                                    <h5>Không có người dùng nào trong thùng rác</h5>
                                </div>
                            </td>
                        </tr>`;
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            });
                        }
                    });
                });

                // Force delete single
                document.addEventListener('click', e => {
                    if (e.target.closest('.btn-force-delete')) {
                        const btn = e.target.closest('.btn-force-delete');
                        const id = btn.dataset.id;
                        const username = btn.dataset.username;
                        Swal.fire({
                            title: `Xóa vĩnh viễn người dùng ${username}?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Xóa'
                        }).then(result => {
                            if (result.isConfirmed) {
                                fetch(`/admin/users/force-delete/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': csrf
                                    }
                                }).then(r => r.json()).then(res => {
                                    document.getElementById(`user-row-${id}`).remove();
                                    Swal.fire({
                                        icon: 'success',
                                        title: res.message
                                    });
                                    if (!document.querySelectorAll('.check-item').length) {
                                        document.getElementById('no-users').style.display =
                                            'table-row';
                                    }
                                });
                            }
                        });
                    }
                });

                // Force delete selected
                btnDeleteSelected.addEventListener('click', () => {
                    const ids = [...checkItems()].filter(ch => ch.checked).map(ch => ch.value);
                    if (!ids.length) return;
                    Swal.fire({
                        title: 'Xóa vĩnh viễn các người dùng đã chọn?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Xóa'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(`/admin/users/force-delete-selected`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    ids
                                })
                            }).then(r => r.json()).then(res => {
                                ids.forEach(id => {
                                    const row = document.getElementById(
                                        `user-row-${id}`);
                                    if (row) row.remove();
                                });
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                                if (!document.querySelectorAll('.check-item').length) {
                                    document.getElementById('no-users').style.display =
                                        'table-row';
                                }
                            });
                        }
                    });
                });

            });
        </script>
    @endpush

    <style>
        .bg-gradient-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%) !important;
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
    </style>
@endsection --}}


{{-- Bản 4 --}}
@extends('layouts.admin')
@section('title', 'Thùng rác người dùng')

@section('content')
    <div class="container-fluid px-4">

        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-danger mb-2">
                        <i class="fa-solid fa-trash text-danger me-2"></i> Thùng rác người dùng
                    </h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                            <li class="breadcrumb-item active">Thùng rác</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4" id="stats-cards">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-danger text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Tổng người dùng trong thùng rác</h6>
                            <h3 class="fw-bold mb-0" id="total-count">{{ $users->total() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fa-solid fa-trash"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Người dùng admin</h6>
                            <h3 class="fw-bold mb-0" id="admin-count">{{ $users->where('role', 'admin')->count() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fa-solid fa-user-shield"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-info text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Người dùng buyer</h6>
                            <h3 class="fw-bold mb-0" id="buyer-count">{{ $users->where('role', 'buyer')->count() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fa-solid fa-user"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="row mb-3">
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-success btn-sm" id="btn-restore-all" {{ $users->total() === 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-arrow-rotate-left me-1"></i> Khôi phục tất cả
                </button>
                <button class="btn btn-danger btn-sm" id="btn-delete-selected" disabled>
                    <i class="fa-solid fa-trash me-1"></i> Xóa đã chọn
                </button>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-center" style="width:50px;">
                                    <input type="checkbox" id="check-all" {{ $users->total() === 0 ? 'disabled' : '' }}>
                                </th>
                                <th class="px-4 py-3">Người dùng</th>
                                <th class="px-4 py-3 text-center">Vai trò</th>
                                <th class="px-4 py-3 text-center">Email</th>
                                <th class="px-4 py-3 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="users-table">
                            @forelse($users as $user)
                                <tr id="user-row-{{ $user->id }}" class="border-bottom">
                                    <td class="text-center px-4">
                                        <input type="checkbox" class="check-item" value="{{ $user->id }}">
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-bold">{{ $user->username }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </td>
                                    <td class="text-center px-4">
                                        <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                    </td>
                                    <td class="text-center px-4">
                                        <span class="badge {{ $user->email_verified_at ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $user->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}
                                        </span>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-success btn-sm btn-restore"
                                                data-id="{{ $user->id }}" title="Khôi phục">
                                                <i class="fa-solid fa-arrow-rotate-left"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-force-delete"
                                                data-id="{{ $user->id }}" data-username="{{ $user->username }}"
                                                title="Xóa vĩnh viễn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr id="no-users">
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            <h5>Không có người dùng nào trong thùng rác</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const checkAll = document.getElementById('check-all');
                const checkItems = () => document.querySelectorAll('.check-item');
                const btnDeleteSelected = document.getElementById('btn-delete-selected');
                const btnRestoreAll = document.getElementById('btn-restore-all');
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Update stats and button state
                const updateUI = () => {
                    const rows = document.querySelectorAll('#users-table tr[id^="user-row-"]');
                    const total = rows.length;
                    document.getElementById('total-count').innerText = total;
                    document.getElementById('admin-count').innerText = [...rows].filter(r => r.querySelector(
                        'td:nth-child(3) .badge').innerText.toLowerCase() == 'admin').length;
                    document.getElementById('buyer-count').innerText = [...rows].filter(r => r.querySelector(
                        'td:nth-child(3) .badge').innerText.toLowerCase() == 'buyer').length;

                    btnRestoreAll.disabled = total === 0;
                    btnDeleteSelected.disabled = ![...checkItems()].some(ch => ch.checked);
                    if (checkAll) checkAll.disabled = total === 0;
                };

                updateUI();

                // Checkbox select
                if (checkAll) checkAll.addEventListener('change', () => {
                    checkItems().forEach(ch => ch.checked = checkAll.checked);
                    updateUI();
                });
                document.addEventListener('change', e => {
                    if (e.target.classList.contains('check-item')) updateUI();
                });

                // Restore / Delete functions
                const removeRow = id => {
                    const row = document.getElementById(`user-row-${id}`);
                    if (row) row.remove();
                    if (!document.querySelectorAll('.check-item').length) {
                        document.getElementById('users-table').innerHTML =
                            `<tr id="no-users"><td colspan="5" class="text-center py-5"><div class="text-muted"><i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i><h5>Không có người dùng nào trong thùng rác</h5></div></td></tr>`;
                    }
                    updateUI();
                };

                const fetchAction = (url, method = 'POST', body = null) => fetch(url, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json'
                    },
                    body: body ? JSON.stringify(body) : null
                }).then(r => r.json());

                // Restore single
                document.addEventListener('click', e => {
                    if (e.target.closest('.btn-restore')) {
                        const btn = e.target.closest('.btn-restore');
                        const id = btn.dataset.id;
                        Swal.fire({
                            title: 'Khôi phục người dùng?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Khôi phục'
                        }).then(res => {
                            if (res.isConfirmed) {
                                fetchAction(`/admin/users/restore/${id}`).then(res => {
                                    removeRow(id);
                                    Swal.fire({
                                        icon: 'success',
                                        title: res.message
                                    });
                                });
                            }
                        });
                    }
                });

                // Restore all
                btnRestoreAll.addEventListener('click', () => {
                    Swal.fire({
                        title: 'Khôi phục tất cả người dùng?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Khôi phục tất cả'
                    }).then(res => {
                        if (res.isConfirmed) {
                            fetchAction(`/admin/users/restore-all`).then(res => {
                                document.getElementById('users-table').innerHTML =
                                    `<tr id="no-users"><td colspan="5" class="text-center py-5"><div class="text-muted"><i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i><h5>Không có người dùng nào trong thùng rác</h5></div></td></tr>`;
                                updateUI();
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            });
                        }
                    });
                });

                // Force delete single
                document.addEventListener('click', e => {
                    if (e.target.closest('.btn-force-delete')) {
                        const btn = e.target.closest('.btn-force-delete');
                        const id = btn.dataset.id;
                        const username = btn.dataset.username;
                        Swal.fire({
                            title: `Xóa vĩnh viễn người dùng ${username}?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Xóa'
                        }).then(res => {
                            if (res.isConfirmed) {
                                fetchAction(`/admin/users/force-delete/${id}`, 'DELETE').then(res => {
                                    removeRow(id);
                                    Swal.fire({
                                        icon: 'success',
                                        title: res.message
                                    });
                                });
                            }
                        });
                    }
                });

                // Force delete selected
                btnDeleteSelected.addEventListener('click', () => {
                    const ids = [...checkItems()].filter(ch => ch.checked).map(ch => ch.value);
                    if (!ids.length) return;
                    Swal.fire({
                        title: 'Xóa vĩnh viễn các người dùng đã chọn?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Xóa'
                    }).then(res => {
                        if (res.isConfirmed) {
                            fetchAction(`/admin/users/force-delete-selected`, 'DELETE', {
                                ids
                            }).then(res => {
                                ids.forEach(removeRow);
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            });
                        }
                    });
                });

            });
        </script>
    @endpush

    <style>
        .bg-gradient-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%) !important;
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

        .badge {
            font-size: 0.85em;
        }

        .d-flex.gap-2 .btn {
            transition: all 0.2s ease;
        }

        .d-flex.gap-2 .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection
