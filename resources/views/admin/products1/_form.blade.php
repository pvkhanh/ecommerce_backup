{{-- <div class="mb-3">
    <label class="form-label">Tên sản phẩm</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Giá</label>
    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? 0) }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Danh mục</label>
    <select name="category_id" class="form-select" required>
        <option value="">-- Chọn danh mục --</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
</div> --}}

{{-- @csrf
<div class="mb-3">
    <label for="name" class="form-label">Tên sản phẩm</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug ?? '') }}">
</div>
<div class="mb-3">
    <label for="price" class="form-label">Giá</label>
    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}"
        required>
</div>
<button type="submit" class="btn btn-primary">Lưu</button>
<a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a> --}}

{{-- -c1 --}}

{{-- @csrf
<div class="mb-3">
    <label class="form-label fw-semibold"><i class="fa-solid fa-box me-1"></i> Tên sản phẩm</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold"><i class="fa-solid fa-dollar-sign me-1"></i> Giá</label>
    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? 0) }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold"><i class="fa-solid fa-layer-group me-1"></i> Danh mục</label>
    <select name="category_id" class="form-select">
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="text-end">
    <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
    </a>
</div> --}}


@php
    $isEdit = isset($product);
@endphp

<div class="mb-3">
    <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
        value="{{ old('name', $isEdit ? $product->name : '') }}" placeholder="Nhập tên sản phẩm">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Mã sản phẩm (SKU)</label>
    <input type="text" name="sku" class="form-control form-control-lg"
        value="{{ old('sku', $isEdit ? $product->sku : '') }}" placeholder="Nhập mã SKU">
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
    <select name="categories[]" class="form-select form-select-lg @error('categories') is-invalid @enderror" multiple>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ collect(old('categories', $isEdit ? $product->categories->pluck('id')->toArray() : []))->contains($category->id) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('categories')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Giữ Ctrl hoặc Cmd để chọn nhiều danh mục.</small>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Giá <span class="text-danger">*</span></label>
    <input type="number" name="price" class="form-control form-control-lg @error('price') is-invalid @enderror"
        value="{{ old('price', $isEdit ? $product->price : '') }}" placeholder="Nhập giá sản phẩm">
    @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
    <select name="status" class="form-select form-select-lg @error('status') is-invalid @enderror">
        <option value="1" {{ old('status', $isEdit ? $product->status : '1') == '1' ? 'selected' : '' }}>Hoạt động
        </option>
        <option value="0" {{ old('status', $isEdit ? $product->status : '') == '0' ? 'selected' : '' }}>Vô hiệu
            hóa</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@if ($isEdit && $product->images()->wherePivot('is_main', true)->first())
    <div class="mb-3">
        <label class="form-label fw-semibold">Hình ảnh hiện tại</label>
        <div>
            <img src="{{ asset('storage/' . $product->images()->wherePivot('is_main', true)->first()->path) }}"
                alt="{{ $product->name }}" class="rounded shadow-sm"
                style="width:100px; height:100px; object-fit:cover;">
        </div>
    </div>
@endif

<div class="mb-3">
    <label class="form-label fw-semibold">{{ $isEdit ? 'Thay đổi' : 'Hình ảnh chính' }}</label>
    <input type="file" name="main_image" class="form-control form-control-lg">
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} btn-lg flex-fill">
        <i
            class="fa-solid fa-{{ $isEdit ? 'pen' : 'plus' }} me-2"></i>{{ $isEdit ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm' }}
    </button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-lg flex-fill">
        <i class="fa-solid fa-rotate-left me-2"></i>Hủy
    </a>
</div>
