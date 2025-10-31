{{-- <div class="mb-3">
    <label class="form-label">Tên người dùng</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Vai trò</label>
    <select name="role" class="form-select" required>
        <option value="user" {{ old('role', $user->role ?? '') === 'user' ? 'selected' : '' }}>User</option>
        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Mật khẩu @if (!isset($user))
            <span class="text-danger">*</span>
        @endif
    </label>
    <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
</div> --}}

{{--
@csrf
<div class="mb-3">
    <label for="name" class="form-label">Họ và tên</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}"
        required>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control"
        value="{{ old('email', $user->email ?? '') }}" required>
</div>

@if (!isset($user))
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
@endif

<div class="mb-3">
    <label for="role" class="form-label">Vai trò</label>
    <select name="role" id="role" class="form-select">
        <option value="user" {{ old('role', $user->role ?? '') === 'user' ? 'selected' : '' }}>Người dùng</option>
        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Quản trị</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Lưu</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a> --}}


{{-- @csrf
<div class="mb-3">
    <label for="avatar" class="form-label">Ảnh đại diện</label>
    <input type="file" class="form-control" name="avatar" id="avatar">
    @if (isset($user))
        <img src="{{ $user->avatar_url }}" alt="Avatar" class="mt-2 rounded-circle" width="80" height="80">
    @endif
</div>

<div class="mb-3">
    <label for="username" class="form-label fw-semibold">
        <i class="fa-solid fa-user me-1 text-primary"></i> Tên đăng nhập
    </label>
    <input type="text" name="username" id="username" class="form-control"
        value="{{ old('username', $user->username ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="email" class="form-label fw-semibold">
        <i class="fa-solid fa-envelope me-1 text-primary"></i> Email
    </label>
    <input type="email" name="email" id="email" class="form-control"
        value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="password" class="form-label fw-semibold">
        <i class="fa-solid fa-lock me-1 text-primary"></i> Mật khẩu
    </label>
    <input type="password" name="password" id="password" class="form-control"
        @if (!isset($user)) required @endif
        placeholder="{{ isset($user) ? 'Để trống nếu không đổi mật khẩu' : '' }}">
</div>

<div class="mb-3">
    <label for="role" class="form-label fw-semibold">
        <i class="fa-solid fa-user-shield me-1 text-primary"></i> Vai trò
    </label>
    <select name="role" id="role" class="form-select" required>
        <option value="">-- Chọn vai trò --</option>
        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
        <option value="staff" {{ old('role', $user->role ?? '') === 'staff' ? 'selected' : '' }}>Nhân viên</option>
        <option value="customer" {{ old('role', $user->role ?? '') === 'customer' ? 'selected' : '' }}>Khách hàng
        </option>
    </select>
</div>

<div class="text-end">
    <button type="submit" class="btn btn-primary px-4">
        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu
    </button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-rotate-left me-1"></i> Hủy
    </a>
</div> --}}


@csrf
@php
    // Nếu đang edit, lấy avatar từ quan hệ images (is_main = true), nếu không có dùng mặc định
    $avatarUrl = isset($user) ? optional($user->images()->wherePivot('is_main', true)->first())->path : null;
    $avatarUrl = $avatarUrl ? asset('storage/' . $avatarUrl) : asset('images/default-avatar.png');
@endphp

<div class="mb-3 text-center">
    <label class="form-label d-block">Ảnh đại diện</label>
    <div class="position-relative d-inline-block">
        <img id="avatarPreview" src="{{ $avatarUrl }}" alt="Avatar"
            class="rounded-circle border border-3 border-primary" width="120" height="120" style="object-fit: cover;">
        <label for="avatar" class="position-absolute bottom-0 end-0 btn btn-warning rounded-circle p-2"
            style="cursor: pointer;">
            <i class="fa-solid fa-camera"></i>
        </label>
    </div>
    <input type="file" class="d-none" name="avatar" id="avatar" accept="image/*" onchange="previewAvatar(event)">
    @error('avatar')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="username" class="form-label fw-semibold">
        <i class="fa-solid fa-user me-1 text-primary"></i> Tên đăng nhập
    </label>
    <input type="text" name="username" id="username" class="form-control"
        value="{{ old('username', $user->username ?? '') }}" required>
    @error('username')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label fw-semibold">
        <i class="fa-solid fa-envelope me-1 text-primary"></i> Email
    </label>
    <input type="email" name="email" id="email" class="form-control"
        value="{{ old('email', $user->email ?? '') }}" required>
    @error('email')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label fw-semibold">
        <i class="fa-solid fa-lock me-1 text-primary"></i> Mật khẩu
    </label>
    <input type="password" name="password" id="password" class="form-control"
        @if (!isset($user)) required @endif
        placeholder="{{ isset($user) ? 'Để trống nếu không đổi mật khẩu' : '' }}">
    @error('password')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="role" class="form-label fw-semibold">
        <i class="fa-solid fa-user-shield me-1 text-primary"></i> Vai trò
    </label>
    <select name="role" id="role" class="form-select" required>
        <option value="">-- Chọn vai trò --</option>
        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
        <option value="staff" {{ old('role', $user->role ?? '') === 'staff' ? 'selected' : '' }}>Nhân viên</option>
        <option value="customer" {{ old('role', $user->role ?? '') === 'customer' ? 'selected' : '' }}>Khách hàng
        </option>
    </select>
    @error('role')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

<div class="text-end">
    <button type="submit" class="btn btn-primary px-4">
        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu
    </button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-rotate-left me-1"></i> Hủy
    </a>
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
        if (file && file.size > 2 * 1024 * 1024) { // 2MB
            toastr.error('Ảnh vượt quá 2MB. Vui lòng chọn ảnh khác!');
            this.value = '';
            document.getElementById('avatarPreview').src = "{{ asset('images/default-avatar.png') }}";
        }
    });
</script>
