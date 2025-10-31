@extends('layouts.admin')

@section('title', isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="h3 mb-3">{{ isset($product) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm' }}</h1>

        <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-8">

                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $product->name ?? '') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                            value="{{ old('sku', $product->sku ?? '') }}">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nếu để trống, hệ thống sẽ tự sinh SKU.</small>
                    </div> --}}
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <div class="input-group">
                            <input type="text" id="sku" name="sku" class="form-control"
                                value="{{ old('sku', $product->sku ?? '') }}" placeholder="Nhập SKU">
                            <button type="button" id="generate-sku" class="btn btn-secondary">Sinh tự động</button>
                        </div>
                        @error('sku')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Giá</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $product->price ?? '') }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" class="form-select">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ str_repeat('--', $cat->depth ?? 0) }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ảnh chính</label>
                        <input type="file" name="main_image" class="form-control">
                        @if (isset($product) && $product->mainImage)
                            <img src="{{ asset('storage/' . $product->mainImage->path) }}" alt=""
                                class="img-thumbnail mt-2" style="width:100px;">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ảnh gallery</label>
                        <input type="file" name="gallery_images[]" multiple class="form-control">
                        @if (isset($product) && $product->galleryImages)
                            <div class="d-flex mt-2 flex-wrap">
                                @foreach ($product->galleryImages as $img)
                                    <img src="{{ asset('storage/' . $img->path) }}" class="img-thumbnail me-2 mb-2"
                                        style="width:80px;">
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                <div class="col-md-4">
                    <h5>Tồn kho</h5>
                    <div id="stock-wrapper">
                        @php
                            // Lấy dữ liệu cũ hoặc từ product, đảm bảo luôn là collection
                            $stocks = old('stock', isset($product) ? $product->stocks?->toArray() ?? [] : []);

                            // Nếu rỗng thì khởi tạo mặc định
                            if (empty($stocks)) {
                                $stocks = [['location' => 'default', 'quantity' => 0]];
                            }
                        @endphp

                        @foreach ($stocks as $i => $s)
                            <div class="stock-item mb-2 d-flex gap-2">
                                <input type="text" name="stock[{{ $i }}][location]" class="form-control"
                                    placeholder="Location" value="{{ $s['location'] ?? 'default' }}">
                                <input type="number" name="stock[{{ $i }}][quantity]" class="form-control"
                                    placeholder="Quantity" value="{{ $s['quantity'] ?? 0 }}">
                                <button type="button" class="btn btn-danger btn-sm remove-stock">X</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-stock">Thêm kho</button>
                </div>
            </div>

            <button class="btn btn-success mt-3">Lưu sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">Hủy</a>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let stockWrapper = document.getElementById('stock-wrapper');
                let addBtn = document.getElementById('add-stock');
                let stockIndex = stockWrapper.children.length;

                addBtn.addEventListener('click', function() {
                    let div = document.createElement('div');
                    div.classList.add('stock-item', 'mb-2', 'd-flex', 'gap-2');
                    div.innerHTML = `
            <input type="text" name="stock[${stockIndex}][location]" class="form-control" placeholder="Location" value="default">
            <input type="number" name="stock[${stockIndex}][quantity]" class="form-control" placeholder="Quantity" value="0">
            <button type="button" class="btn btn-danger btn-sm remove-stock">X</button>
        `;
                    stockWrapper.appendChild(div);
                    stockIndex++;
                });

                stockWrapper.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-stock')) {
                        e.target.closest('.stock-item').remove();
                    }
                });
            });
        </script>
        <script>
            document.getElementById('generate-sku').addEventListener('click', function() {
                const prefix = 'PRD-'; // tiền tố tùy bạn
                const random = Math.floor(Math.random() * 90000) + 10000; // 5 chữ số ngẫu nhiên
                document.getElementById('sku').value = prefix + random;
            });
        </script>
    @endpush
@endsection
