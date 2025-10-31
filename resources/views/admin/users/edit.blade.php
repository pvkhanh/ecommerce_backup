{{-- @extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
    <div class="page-heading mb-4">
        <h3 class="fw-bold text-primary">
            <i class="fa-solid fa-user-pen me-2"></i> Chỉnh sửa người dùng
        </h3>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @method('PUT')
                @include('admin.users._form')
            </form>
        </div>
    </div>
@endsection --}}

{{-- Bản 2 --}}
{{-- @extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary mb-0">
                <i class="fa-solid fa-user-pen me-2"></i> Chỉnh sửa người dùng
            </h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <!-- Avatar -->
                        <div class="col-md-4 text-center">
                            <label for="avatar" class="form-label fw-semibold">Ảnh đại diện</label>
                            <div class="mb-3">
                                <img id="avatarPreview"
                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                    class="rounded-circle border" width="120" height="120" style="object-fit: cover;">
                            </div>
                            <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*"
                                onchange="previewAvatar(event)">
                        </div>

                        <!-- Thông tin -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="{{ old('first_name', $user->first_name) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tên <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="{{ old('last_name', $user->last_name) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control"
                                        value="{{ old('username', $user->username) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gender" class="form-select">
                                        <option value="">-- Chọn --</option>
                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" name="birthday" class="form-control"
                                        value="{{ old('birthday', optional($user->birthday)->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select">
                                        @foreach (\App\Enums\UserRole::cases() as $role)
                                            <option value="{{ $role->value }}"
                                                {{ $user->role === $role->value ? 'selected' : '' }}>
                                                {{ ucfirst($role->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Vô hiệu</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Giới thiệu</label>
                            <textarea name="bio" rows="3" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa-solid fa-save me-1"></i> Cập nhật người dùng
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




{{-- Bản 3: 23/10/2025 --}}

@extends('layouts.admin')

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
                                <img id="avatarPreview"
                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
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
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin - Quản trị
                                        viên</option>
                                    <option value="buyer" {{ $user->role == 'buyer' ? 'selected' : '' }}>Buyer - Người mua
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
                                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>✅ Hoạt động</option>
                                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>❌ Vô hiệu hóa</option>
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

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-lock text-warning me-1"></i>
                                        Mật khẩu
                                    </label>
                                    <input type="password" name="password" class="form-control form-control-lg">
                                    <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-lock-open text-warning me-1"></i>
                                        Xác nhận mật khẩu
                                    </label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control form-control-lg">
                                </div>

                                <!-- Email Verified -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fa-solid fa-envelope-circle-check text-success me-1"></i>
                                        Xác thực email
                                    </label>
                                    <input type="datetime-local" name="email_verified_at"
                                        class="form-control form-control-lg"
                                        value="{{ old('email_verified_at', optional($user->email_verified_at)->format('Y-m-d\TH:i')) }}">
                                    <small class="text-muted">Để trống nếu chưa xác thực email</small>
                                    @error('email_verified_at')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
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
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Họ <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control form-control-lg"
                                        value="{{ old('first_name', $user->first_name) }}" required>
                                    @error('first_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tên <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control form-control-lg"
                                        value="{{ old('last_name', $user->last_name) }}" required>
                                    @error('last_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control form-control-lg"
                                        value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Giới tính</label>
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

                                <!-- Birthday -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Ngày sinh</label>
                                    <input type="date" name="birthday" class="form-control form-control-lg"
                                        value="{{ old('birthday', optional($user->birthday)->format('Y-m-d')) }}"
                                        max="{{ date('Y-m-d') }}">
                                    @error('birthday')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Bio -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Giới thiệu bản thân</label>
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
                                    <i class="fa-solid fa-pen me-2"></i>Cập nhật
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
                if (file.size > 2 * 1024 * 1024) {
                    toastr.error('Ảnh vượt quá 2MB. Vui lòng chọn ảnh khác!');
                    event.target.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('avatarPreview').src = e.target.result;
                    document.getElementById('removeAvatarBtn').classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        }

        // Xoá avatar hiện tại về mặc định
        function removeAvatar() {
            document.getElementById('avatarPreview').src = "{{ asset('images/default-avatar.png') }}";
            document.getElementById('avatar').value = '';
            document.getElementById('removeAvatarBtn').classList.add('d-none');
        }

        // Thêm nút xoá avatar
        document.addEventListener('DOMContentLoaded', function() {
            const avatarContainer = document.querySelector('#avatarPreview').parentElement;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.id = 'removeAvatarBtn';
            btn.className = 'btn btn-danger btn-sm position-absolute top-0 start-0 d-none';
            btn.style.width = '35px';
            btn.style.height = '35px';
            btn.innerHTML = '<i class="fa-solid fa-trash"></i>';
            btn.onclick = removeAvatar;
            avatarContainer.appendChild(btn);

            // Hiển thị nút xoá nếu avatar hiện tại không phải mặc định
            if (document.getElementById('avatarPreview').src.indexOf('default-avatar.png') === -1) {
                btn.classList.remove('d-none');
            }
        });

        // // Xác nhận trước khi submit form
        // document.querySelector('form').addEventListener('submit', function(e) {
        //     if (!confirm('Bạn có chắc chắn muốn cập nhật người dùng này?')) {
        //         e.preventDefault();
        //     }
        // });
    </script>

@endsection
