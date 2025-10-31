{{-- @extends('layouts.admin')

@section('title', isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm')

@section('content')
    <div class="container-fluid">
        <h1>{{ isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm' }}</h1>

        <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $product->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="sku" class="form-label">SKU</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="sku" name="sku"
                        value="{{ old('sku', $product->sku ?? 'AUTO') }}" readonly>
                    <button type="button" class="btn btn-outline-secondary" id="generateSku">Sinh SKU</button>
                </div>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Danh mục</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @if (old('category_id', $product->category_id ?? '') == $cat->id) selected @endif>
                            {{ str_repeat('--', $cat->depth ?? 0) }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" min="0" class="form-control" id="price" name="price"
                    value="{{ old('price', $product->price ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh chính</label>
                <input type="file" name="main_image" class="form-control" accept="image/*">
                @if (isset($product) && $product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="img-thumbnail mt-2"
                        style="width:100px; height:100px; object-fit:cover;">
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Gallery ảnh</label>
                <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                @if (isset($product) && $product->images->count() > 1)
                    <div class="mt-2 d-flex flex-wrap">
                        @foreach ($product->images->skip(1) as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" class="img-thumbnail me-2 mb-2"
                                style="width:80px; height:80px; object-fit:cover;">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Tồn kho (theo variant/location)</label>
                <div id="stock-container">
                    @if (old('stock', $product->stock_items ?? false))
                        @foreach (old('stock', $product->stock_items) as $idx => $stock)
                            <div class="d-flex mb-2 stock-row">
                                <input type="text" name="stock[{{ $idx }}][location]" class="form-control me-2"
                                    placeholder="Vị trí" value="{{ $stock['location'] }}">
                                <input type="number" name="stock[{{ $idx }}][quantity]" class="form-control me-2"
                                    placeholder="Số lượng" value="{{ $stock['quantity'] }}">
                                <button type="button" class="btn btn-danger remove-stock">Xóa</button>
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex mb-2 stock-row">
                            <input type="text" name="stock[0][location]" class="form-control me-2" placeholder="Vị trí"
                                value="default">
                            <input type="number" name="stock[0][quantity]" class="form-control me-2" placeholder="Số lượng"
                                value="0">
                            <button type="button" class="btn btn-danger remove-stock">Xóa</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-outline-primary mt-2" id="addStock">Thêm kho</button>
            </div>

            <button type="submit" class="btn btn-success">{{ isset($product) ? 'Cập nhật' : 'Tạo mới' }}</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

@section('scripts')
    <script>
        document.getElementById('generateSku').addEventListener('click', function() {
            const sku = 'SKU' + Math.floor(Math.random() * 999999);
            document.getElementById('sku').value = sku;
        });

        // Thêm/xóa stock rows
        let stockIdx = {{ isset($product) && $product->stock_items ? count($product->stock_items) : 1 }};
        document.getElementById('addStock').addEventListener('click', function() {
            const container = document.getElementById('stock-container');
            const row = document.createElement('div');
            row.classList.add('d-flex', 'mb-2', 'stock-row');
            row.innerHTML = `
        <input type="text" name="stock[${stockIdx}][location]" class="form-control me-2" placeholder="Vị trí" value="default">
        <input type="number" name="stock[${stockIdx}][quantity]" class="form-control me-2" placeholder="Số lượng" value="0">
        <button type="button" class="btn btn-danger remove-stock">Xóa</button>
    `;
            container.appendChild(row);
            stockIdx++;
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-stock')) {
                e.target.closest('.stock-row').remove();
            }
        });
    </script>
@endsection --}}
