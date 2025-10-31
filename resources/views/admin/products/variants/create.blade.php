{{-- @extends('admin.layouts.app') --}}
@extends('layouts.admin')
@section('title', 'Thêm biến thể')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h1 class="h3 mb-0">Thêm biến thể cho: {{ $product->name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.variants.index', $product) }}">Biến thể</a>
                    </li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin biến thể</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Tên biến thể <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="VD: Đỏ - Size M, Xanh - Size L, v.v." required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Tên mô tả đặc điểm của biến thể (màu sắc, kích thước, phiên bản,...)
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SKU (Mã sản phẩm) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="sku" id="sku"
                                        class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}"
                                        placeholder="VD: SP-001-RM" required>
                                    <button type="button" id="generate-sku" class="btn btn-outline-secondary">
                                        <i class="fas fa-wand-magic-sparkles"></i> Tạo tự động
                                    </button>
                                </div>
                                <small id="sku-feedback" class="form-text text-muted">
                                    Mã duy nhất để quản lý kho. Không được trùng với biến thể khác.
                                </small>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Giá <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
                                    <span class="input-group-text">VNĐ</span>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    Giá gốc sản phẩm: <strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                                </small>
                            </div>

                            <hr class="my-4">

                            <h6 class="mb-3">Tồn kho ban đầu (không bắt buộc)</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Số lượng</label>
                                        <input type="number" name="stock_quantity"
                                            class="form-control @error('stock_quantity') is-invalid @enderror"
                                            value="{{ old('stock_quantity', 0) }}" min="0">
                                        @error('stock_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Vị trí kho</label>
                                        <input type="text" name="stock_location"
                                            class="form-control @error('stock_location') is-invalid @enderror"
                                            value="{{ old('stock_location', 'default') }}"
                                            placeholder="VD: Kho A, Kệ 1, v.v.">
                                        @error('stock_location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Lưu biến thể
                                </button>
                                <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin sản phẩm gốc</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Tên:</dt>
                            <dd class="col-sm-7">{{ $product->name }}</dd>

                            <dt class="col-sm-5">Giá gốc:</dt>
                            <dd class="col-sm-7">{{ number_format($product->price, 0, ',', '.') }}đ</dd>

                            <dt class="col-sm-5">Biến thể:</dt>
                            <dd class="col-sm-7">{{ $product->variants->count() }}</dd>

                            <dt class="col-sm-5">Tồn kho:</dt>
                            <dd class="col-sm-7">{{ $product->total_stock ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Gợi ý đặt tên</h5>
                    </div>
                    <div class="card-body">
                        <p class="small mb-2"><strong>Ví dụ về tên biến thể:</strong></p>
                        <ul class="small mb-0">
                            <li>Đỏ - Size M</li>
                            <li>Xanh dương - Size L</li>
                            <li>Vàng - Size XL</li>
                            <li>Bạc - 128GB</li>
                            <li>Đen - 256GB</li>
                        </ul>

                        <hr class="my-3">

                        <p class="small mb-2"><strong>Gợi ý SKU:</strong></p>
                        <ul class="small mb-0">
                            <li>{{ strtoupper(Str::slug($product->name)) }}-RED-M</li>
                            <li>{{ strtoupper(Str::slug($product->name)) }}-BLUE-L</li>
                            <li>{{ strtoupper(Str::slug($product->name)) }}-128GB</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const skuInput = document.getElementById('sku');
            const skuFeedback = document.getElementById('sku-feedback');
            const generateBtn = document.getElementById('generate-sku');

            // ✅ Tạo SKU tự động dựa theo tên sản phẩm và thời gian
            generateBtn.addEventListener('click', function() {
                const productName = "{{ Str::slug($product->name, '-') }}";
                const randomSuffix = Math.floor(1000 + Math.random() * 9000);
                const newSku = productName.toUpperCase() + '-' + randomSuffix;
                skuInput.value = newSku;
                checkSkuUnique(newSku);
            });

            // ✅ Kiểm tra trùng SKU mỗi khi người dùng nhập
            skuInput.addEventListener('input', function() {
                const value = this.value.trim();
                if (value.length > 0) {
                    checkSkuUnique(value);
                } else {
                    skuFeedback.textContent =
                        'Mã duy nhất để quản lý kho. Không được trùng với biến thể khác.';
                    skuFeedback.className = 'form-text text-muted';
                }
            });

            // ✅ Hàm kiểm tra trùng SKU bằng AJAX
            function checkSkuUnique(sku) {
                fetch("{{ route('admin.products.variants.checkSku', $product) }}?sku=" + encodeURIComponent(sku))
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            skuFeedback.textContent = "⚠️ Mã SKU này đã tồn tại. Vui lòng chọn mã khác.";
                            skuFeedback.className = "form-text text-danger";
                        } else {
                            skuFeedback.textContent = "✅ Mã SKU hợp lệ.";
                            skuFeedback.className = "form-text text-success";
                        }
                    })
                    .catch(() => {
                        skuFeedback.textContent = "Không thể kiểm tra SKU. Vui lòng thử lại.";
                        skuFeedback.className = "form-text text-warning";
                    });
            }
        });
    </script>
@endpush
