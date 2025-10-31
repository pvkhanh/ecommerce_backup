{{-- @extends('admin.layouts.app') --}}
@extends('layouts.admin')
@section('title', 'Chỉnh sửa biến thể')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h1 class="h3 mb-0">Chỉnh sửa biến thể: {{ $variant->name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.variants.index', $product) }}">Biến thể</a>
                    </li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
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
                        <form action="{{ route('admin.products.variants.update', [$product, $variant]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Tên biến thể <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $variant->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                                    value="{{ old('sku', $variant->sku) }}" required>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price', $variant->price) }}" min="0" step="0.01" required>
                                    <span class="input-group-text">VNĐ</span>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật
                                </button>
                                <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                                <a href="{{ route('admin.products.variants.stock', [$product, $variant]) }}"
                                    class="btn btn-success ms-auto">
                                    <i class="fas fa-boxes"></i> Quản lý kho
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin tồn kho</h5>
                    </div>
                    <div class="card-body">
                        @if ($variant->stockItems->count() > 0)
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Vị trí</th>
                                        <th class="text-end">Số lượng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variant->stockItems as $stock)
                                        <tr>
                                            <td>{{ $stock->location }}</td>
                                            <td class="text-end">
                                                <span class="badge bg-{{ $stock->quantity > 0 ? 'success' : 'danger' }}">
                                                    {{ $stock->quantity }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td><strong>Tổng:</strong></td>
                                        <td class="text-end">
                                            <strong>{{ $variant->stockItems->sum('quantity') }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <p class="mb-0">Chưa có tồn kho</p>
                            </div>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('admin.products.variants.stock', [$product, $variant]) }}"
                                class="btn btn-success w-100">
                                <i class="fas fa-boxes"></i> Quản lý kho
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">Xóa biến thể</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            Xóa biến thể sẽ xóa luôn tất cả tồn kho. Hành động này không thể hoàn tác.
                        </p>
                        <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa biến thể này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Xóa biến thể
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
