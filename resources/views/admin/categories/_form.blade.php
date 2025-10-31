{{-- <div class="mb-3">
    <label class="form-label">Tên danh mục</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
</div> --}}

{{--
@csrf
<div class="mb-3">
    <label class="form-label">Tên danh mục</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}" required>
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="is_active" class="form-check-input"
        {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label">Kích hoạt</label>
</div>

<button type="submit" class="btn btn-primary">Lưu</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a> --}}

{{--
@csrf
<div class="mb-3">
    <label for="name" class="form-label">Tên danh mục</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}" required>
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
        {{ old('is_active', $category->is_active ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Kích hoạt</label>
</div>
<button type="submit" class="btn btn-primary">Lưu</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a> --}}


@csrf
<div class="mb-3">
    <label class="form-label fw-semibold"><i class="fa-solid fa-folder-open me-1"></i> Tên danh mục</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold"><i class="fa-solid fa-link me-1"></i> Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold"><i class="fa-solid fa-toggle-on me-1"></i> Trạng thái</label>
    <select name="is_active" class="form-select">
        <option value="1" {{ old('is_active', $category->is_active ?? true) ? 'selected' : '' }}>Hoạt động</option>
        <option value="0" {{ old('is_active', $category->is_active ?? true) ? '' : 'selected' }}>Ẩn</option>
    </select>
</div>

<div class="text-end">
    <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div>
