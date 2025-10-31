{{-- @extends('layouts.admin')

@section('title', 'Thêm người dùng')

@section('content')
    <h4 class="mb-3">Thêm người dùng mới</h4>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users._form')
        <button type="submit" class="btn btn-success mt-2">Lưu</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-2">Hủy</a>
    </form>
@endsection --}}

{{-- @extends('layouts.admin')

@section('title', 'Thêm người dùng mới')

@section('content')
    <div class="page-heading mb-4">
        <h3 class="fw-bold text-primary">
            <i class="fa-solid fa-user-plus me-2"></i> Thêm người dùng mới
        </h3>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @include('admin.users._form')
            </form>
        </div>
    </div>
@endsection --}}



{{-- Bản 2: ok và hoàn chỉnh --}}
{{-- @extends('layouts.admin')

@section('title', 'Thêm người dùng mới')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary mb-0">
                <i class="fa-solid fa-user-plus me-2"></i> Thêm người dùng mới
            </h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <!-- Avatar -->
                        <div class="col-md-4 text-center">
                            <label for="avatar" class="form-label fw-semibold">Ảnh đại diện</label>
                            <div class="mb-3">
                                <img id="avatarPreview" src="{{ asset('images/default-avatar.png') }}"
                                    class="rounded-circle border" width="120" height="120" style="object-fit: cover;">
                            </div>
                            <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*"
                                onchange="previewAvatar(event)">
                        </div>

                        <!-- Thông tin cá nhân -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="{{ old('first_name') }}">
                                    @error('first_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tên <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control"
                                        value="{{ old('username') }}">
                                    @error('username')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control">
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gender" class="form-select">
                                        <option value="">-- Chọn --</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" name="birthday" class="form-control"
                                        value="{{ old('birthday') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select">
                                        @foreach (\App\Enums\UserRole::cases() as $role)
                                            <option value="{{ $role->value }}"
                                                {{ old('role') == $role->value ? 'selected' : '' }}>
                                                {{ ucfirst($role->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Hoạt động
                                        </option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Vô hiệu
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="col-12">
                            <label class="form-label">Giới thiệu</label>
                            <textarea name="bio" rows="3" class="form-control">{{ old('bio') }}</textarea>
                        </div>

                        <!-- Nút hành động -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu người dùng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('avatarPreview').src = URL.createObjectURL(file);
            }
        }
    </script>
@endsection --}}



{{-- Bản 3: 23/10/2025 cả create và edit --}}
{{-- @extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-user-pen text-warning me-2"></i>
                            Chỉnh sửa người dùng
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-info btn-lg">
                            <i class="fa-solid fa-eye me-2"></i> Xem chi tiết
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Left Column - Avatar & Basic Info -->
                <div class="col-lg-4">
                    <!-- Avatar Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-image text-primary me-2"></i>Ảnh đại diện
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="position-relative d-inline-block mb-3">
                                @php
                                    $avatar =
                                        optional($user->images()->wherePivot('is_main', true)->first())->path ?? null;
                                    $avatarUrl = $avatar
                                        ? asset('storage/' . $avatar)
                                        : asset('images/default-avatar.png');
                                @endphp
                                <img id="avatarPreview" src="{{ $avatarUrl }}"
                                    class="rounded-circle border border-4 border-primary shadow-sm"
                                    style="width: 180px; height: 180px; object-fit: cover;">
                                <label for="avatar"
                                    class="position-absolute bottom-0 end-0 btn btn-warning rounded-circle shadow"
                                    style="width: 45px; height: 45px; cursor: pointer;">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                            </div>
                            <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*"
                                onchange="previewAvatar(event)">
                            <p class="text-muted small mb-0">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Định dạng: JPG, PNG (Max: 2MB)
                            </p>
                            @error('avatar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Account Info Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-info-circle text-primary me-2"></i>Thông tin tài khoản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">ID người dùng</small>
                                <h5 class="mb-0">#{{ $user->id }}</h5>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ngày tạo</small>
                                <h6 class="mb-0">{{ $user->created_at->format('d/m/Y H:i') }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Cập nhật lần cuối</small>
                                <h6 class="mb-0">{{ $user->updated_at->format('d/m/Y H:i') }}</h6>
                            </div>
                            @if ($user->email_verified_at)
                                <div>
                                    <small class="text-muted d-block mb-1">Email xác thực</small>
                                    <span class="badge bg-success">
                                        <i class="fa-solid fa-check-circle me-1"></i>
                                        {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Role & Status Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-cog text-primary me-2"></i>Cài đặt tài khoản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-user-shield text-danger me-1"></i>
                                    Vai trò <span class="text-danger">*</span>
                                </label>
                                <select name="role" class="form-select form-select-lg" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Admin - Quản trị viên
                                    </option>
                                    <option value="buyer" {{ old('role', $user->role) == 'buyer' ? 'selected' : '' }}>
                                        Buyer - Người mua
                                    </option>
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-toggle-on text-success me-1"></i>
                                    Trạng thái <span class="text-danger">*</span>
                                </label>
                                <select name="is_active" class="form-select form-select-lg" required>
                                    <option value="1"
                                        {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>
                                        ✅ Hoạt động
                                    </option>
                                    <option value="0"
                                        {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>
                                        ❌ Vô hiệu hóa
                                    </option>
                                </select>
                                @error('is_active')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Form Details -->
                <div class="col-lg-8">
                    <!-- Account Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-key text-primary me-2"></i>Thông tin đăng nhập
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-user text-primary me-1"></i>
                                        Tên đăng nhập <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="username" class="form-control form-control-lg"
                                        value="{{ old('username', $user->username) }}" required>
                                    @error('username')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-envelope text-info me-1"></i>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" class="form-control form-control-lg"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info mb-0">
                                        <i class="fa-solid fa-info-circle me-2"></i>
                                        <strong>Đổi mật khẩu:</strong> Chỉ điền nếu muốn thay đổi. Để trống nếu giữ nguyên
                                        mật khẩu cũ.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-lock text-warning me-1"></i>
                                        Mật khẩu mới
                                    </label>
                                    <input type="password" name="password" class="form-control form-control-lg"
                                        placeholder="Để trống nếu không đổi">
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-lock-open text-warning me-1"></i>
                                        Xác nhận mật khẩu mới
                                    </label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control form-control-lg" placeholder="Nhập lại mật khẩu mới">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-id-card text-primary me-2"></i>Thông tin cá nhân
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-phone text-success me-1"></i>
                                        Số điện thoại
                                    </label>
                                    <input type="text" name="phone" class="form-control form-control-lg"
                                        value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-venus-mars text-info me-1"></i>
                                        Giới tính
                                    </label>
                                    <select name="gender" class="form-select form-select-lg">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="male"
                                            {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female"
                                            {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other"
                                            {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-cake-candles text-warning me-1"></i>
                                        Ngày sinh
                                    </label>
                                    <input type="date" name="birthday" class="form-control form-control-lg"
                                        value="{{ old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '') }}"
                                        max="{{ date('Y-m-d') }}">
                                    @error('birthday')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-comment text-primary me-1"></i>
                                        Giới thiệu bản thân
                                    </label>
                                    <textarea name="bio" rows="4" class="form-control form-control-lg">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fa-solid fa-times me-2"></i>Hủy bỏ
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg px-4">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Cập nhật
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // ✅ Preview ảnh đại diện khi chọn file
        function previewAvatar(event) {
            const [file] = event.target.files;
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // ✅ Optional: Validate kích thước file (2MB max)
        document.getElementById('avatar').addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.size > 2 * 1024 * 1024) { // 2MB
                toastr.error('Ảnh vượt quá 2MB. Vui lòng chọn ảnh khác!');
                this.value = ''; // reset input
                document.getElementById('avatarPreview').src = "{{ $avatarUrl }}"; // reset ảnh cũ
            }
        });
    </script> --}}


{{-- Bản 4: riêng create --}}

@extends('layouts.admin')

@section('title', 'Tạo mới người dùng')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-user-plus text-success me-2"></i>
                            Tạo mới người dùng
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                                <li class="breadcrumb-item active">Tạo mới</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <!-- Left Column - Avatar & Role/Status -->
                <div class="col-lg-4">
                    <!-- Avatar Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-image text-primary me-2"></i>Ảnh đại diện
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="position-relative d-inline-block mb-3">
                                <img id="avatarPreview" src="{{ asset('images/default-avatar.png') }}"
                                    class="rounded-circle border border-4 border-primary shadow-sm"
                                    style="width: 180px; height: 180px; object-fit: cover;">
                                <label for="avatar"
                                    class="position-absolute bottom-0 end-0 btn btn-warning rounded-circle shadow"
                                    style="width: 45px; height: 45px; cursor: pointer;">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                            </div>
                            <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*"
                                onchange="previewAvatar(event)">
                            <p class="text-muted small mb-0">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Định dạng: JPG, PNG (Max: 2MB)
                            </p>
                            @error('avatar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Role & Status Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-cog text-primary me-2"></i>Cài đặt tài khoản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-user-shield text-danger me-1"></i>
                                    Vai trò <span class="text-danger">*</span>
                                </label>
                                <select name="role" class="form-select form-select-lg" required>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                        Admin - Quản trị viên
                                    </option>
                                    <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>
                                        Buyer - Người mua
                                    </option>
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-toggle-on text-success me-1"></i>
                                    Trạng thái <span class="text-danger">*</span>
                                </label>
                                <select name="is_active" class="form-select form-select-lg" required>
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                                        ✅ Hoạt động
                                    </option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>
                                        ❌ Vô hiệu hóa
                                    </option>
                                </select>
                                @error('is_active')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Form Details -->
                <div class="col-lg-8">
                    <!-- Account Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-key text-primary me-2"></i>Thông tin đăng nhập
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-user text-primary me-1"></i>
                                        Tên đăng nhập <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="username" class="form-control form-control-lg"
                                        value="{{ old('username') }}" required>
                                    @error('username')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-envelope text-info me-1"></i>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" class="form-control form-control-lg"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-lock text-warning me-1"></i>
                                        Mật khẩu <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="password" class="form-control form-control-lg" required>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-lock-open text-warning me-1"></i>
                                        Xác nhận mật khẩu <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control form-control-lg" required>
                                </div>

                                <!-- Email Verified -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-envelope-circle-check text-success me-1"></i>
                                        Xác thực email
                                    </label>
                                    <input type="datetime-local" name="email_verified_at"
                                        class="form-control form-control-lg" value="{{ old('email_verified_at') }}">
                                    <small class="text-muted">Để trống nếu chưa xác thực email</small>
                                    @error('email_verified_at')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Remember Token (ẩn) -->
                                <input type="hidden" name="remember_token" value="{{ Str::random(60) }}">
                            </div>
                        </div>
                    </div>


                    <!-- Personal Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-solid fa-id-card text-primary me-2"></i>Thông tin cá nhân
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-user text-primary me-1"></i>
                                        Họ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="first_name" class="form-control form-control-lg"
                                        value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-user text-primary me-1"></i>
                                        Tên <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="last_name" class="form-control form-control-lg"
                                        value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Số điện thoại -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-phone text-success me-1"></i>
                                        Số điện thoại
                                    </label>
                                    <input type="text" name="phone" class="form-control form-control-lg"
                                        value="{{ old('phone') }}">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Giới tính -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-venus-mars text-info me-1"></i>
                                        Giới tính
                                    </label>
                                    <select name="gender" class="form-select form-select-lg">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam
                                        </option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ
                                        </option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác
                                        </option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Ngày sinh -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-cake-candles text-warning me-1"></i>
                                        Ngày sinh
                                    </label>
                                    <input type="date" name="birthday" class="form-control form-control-lg"
                                        value="{{ old('birthday') }}" max="{{ date('Y-m-d') }}">
                                    @error('birthday')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Giới thiệu bản thân -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-comment text-primary me-1"></i>
                                        Giới thiệu bản thân
                                    </label>
                                    <textarea name="bio" rows="4" class="form-control form-control-lg">{{ old('bio') }}</textarea>
                                    @error('bio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fa-solid fa-times me-2"></i>Hủy bỏ
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-4">
                                    <i class="fa-solid fa-plus me-2"></i>Tạo mới
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewAvatar(event) {
            const [file] = event.target.files;
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        document.getElementById('avatar').addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                toastr.error('Ảnh vượt quá 2MB. Vui lòng chọn ảnh khác!');
                this.value = '';
                document.getElementById('avatarPreview').src = "{{ asset('images/default-avatar.png') }}";
            }
        });
    </script>
@endsection
