{{-- @php
    $isEdit = isset($product);
@endphp

@extends('layouts.admin')

@section('title', $isEdit ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">{{ $isEdit ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' }}</h1>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        </div>

        <form action="{{ $isEdit ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="row g-3">

                <!-- Tên sản phẩm -->
                <div class="col-md-6">
                    <label for="name" class="form-label">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $product->name ?? '') }}" required>
                </div>

                <!-- SKU -->
                <div class="col-md-6">
                    <label for="sku" class="form-label">SKU</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="sku" name="sku"
                            value="{{ old('sku', $product->sku ?? '') }}" required>
                        <button type="button" class="btn btn-outline-secondary" id="generateSku">Tự sinh</button>
                    </div>
                </div>

                <!-- Giá -->
                <div class="col-md-6">
                    <label for="price" class="form-label">Giá (VNĐ)</label>
                    <input type="number" class="form-control" id="price" name="price"
                        value="{{ old('price', $product->price ?? '') }}" required>
                </div>

                <!-- Danh mục -->
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Danh mục</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {!! str_repeat('&nbsp;&nbsp;', $category->depth ?? 0) !!} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mô tả -->
                <div class="col-12">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <!-- Ảnh chính -->
                <div class="col-md-6">
                    <label for="main_image" class="form-label">Ảnh chính</label>
                    <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*">
                    @if ($isEdit && $product->mainImage)
                        <img src="{{ asset('storage/' . $product->mainImage->path) }}" alt="Ảnh chính"
                            class="img-thumbnail mt-2" style="width:120px; height:120px;">
                    @endif
                </div>

                <!-- Ảnh gallery -->
                <div class="col-md-6">
                    <label for="gallery_images" class="form-label">Ảnh gallery</label>
                    <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" accept="image/*"
                        multiple>
                    @if ($isEdit && $product->galleryImages)
                        <div class="d-flex flex-wrap mt-2">
                            @foreach ($product->galleryImages as $img)
                                <img src="{{ asset('storage/' . $img->path) }}" class="img-thumbnail me-2 mb-2"
                                    style="width:80px; height:80px;">
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tồn kho -->
                <div class="col-12">
                    <label class="form-label">Tồn kho theo kho</label>
                    <div id="stockContainer">
                        @php
                            $stockItems = old('stock', $product->stockItems ?? []);
                        @endphp

                        @if (count($stockItems))
                            @foreach ($stockItems as $index => $stock)
                                <div class="row mb-2 stock-row">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control"
                                            name="stock[{{ $index }}][location]" placeholder="Tên kho"
                                            value="{{ $stock['location'] ?? ($stock->location ?? '') }}" required>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" class="form-control"
                                            name="stock[{{ $index }}][quantity]" placeholder="Số lượng"
                                            value="{{ $stock['quantity'] ?? ($stock->quantity ?? '') }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger removeStock">Xóa</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mb-2 stock-row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="stock[0][location]"
                                        placeholder="Tên kho" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" class="form-control" name="stock[0][quantity]"
                                        placeholder="Số lượng" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger removeStock">Xóa</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" id="addStock">Thêm kho</button>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('generateSku')?.addEventListener('click', function() {
            const sku = 'SKU-' + Math.random().toString(36).substring(2, 8).toUpperCase();
            document.getElementById('sku').value = sku;
        });

        document.getElementById('addStock')?.addEventListener('click', function() {
            const container = document.getElementById('stockContainer');
            const index = container.querySelectorAll('.stock-row').length;
            const row = document.createElement('div');
            row.className = 'row mb-2 stock-row';
            row.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="stock[${index}][location]" placeholder="Tên kho" required>
            </div>
            <div class="col-md-5">
                <input type="number" class="form-control" name="stock[${index}][quantity]" placeholder="Số lượng" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger removeStock">Xóa</button>
            </div>
        `;
            container.appendChild(row);
        });

        document.getElementById('stockContainer')?.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('removeStock')) {
                e.target.closest('.stock-row').remove();
            }
        });
    </script>
@endsection --}}
