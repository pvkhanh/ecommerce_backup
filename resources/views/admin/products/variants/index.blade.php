{{-- @extends('admin.layouts.app') --}}
@extends('layouts.admin')
@section('title', 'Quản lý biến thể')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Biến thể: {{ $product->name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.products.show', $product) }}">{{ $product->name }}</a></li>
                            <li class="breadcrumb-item active">Biến thể</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
                        <i class="fas fa-plus-circle"></i> Tạo nhiều biến thể
                    </button>
                    <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm biến thể
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Product Info Card -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-2">{{ $product->name }}</h5>
                                <p class="text-muted mb-0">
                                    <strong>Giá gốc:</strong> {{ number_format($product->price, 0, ',', '.') }}đ |
                                    <strong>Tổng biến thể:</strong> {{ $variants->count() }} |
                                    <strong>Tổng tồn kho:</strong>
                                    {{ $variants->sum(fn($v) => $v->stockItems->sum('quantity')) }}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('admin.products.show', $product) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại sản phẩm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variants Table -->
        <div class="card">
            <div class="card-body">
                @if ($variants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Tên biến thể</th>
                                    <th>SKU</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th>Kho</th>
                                    <th style="width: 200px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($variants as $variant)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $variant->name }}</strong>
                                        </td>
                                        <td>
                                            <code>{{ $variant->sku }}</code>
                                        </td>
                                        <td>
                                            <strong
                                                class="text-success">{{ number_format($variant->price, 0, ',', '.') }}đ</strong>
                                            @if ($variant->price != $product->price)
                                                <br>
                                                <small class="text-muted">
                                                    ({{ $variant->price > $product->price ? '+' : '' }}{{ number_format($variant->price - $product->price, 0, ',', '.') }}đ)
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $totalStock = $variant->stockItems->sum('quantity');
                                            @endphp
                                            <span
                                                class="badge bg-{{ $totalStock > 10 ? 'success' : ($totalStock > 0 ? 'warning' : 'danger') }} fs-6">
                                                {{ $totalStock }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($variant->stockItems->count() > 0)
                                                @foreach ($variant->stockItems as $stock)
                                                    <small class="d-block">
                                                        <i class="fas fa-warehouse text-muted"></i>
                                                        {{ $stock->location }}: {{ $stock->quantity }}
                                                    </small>
                                                @endforeach
                                            @else
                                                <small class="text-muted">Chưa có</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.products.variants.stock', [$product, $variant]) }}"
                                                    class="btn btn-outline-success" title="Quản lý kho">
                                                    <i class="fas fa-boxes"></i>
                                                </a>
                                                <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                                    class="btn btn-outline-primary" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form
                                                    action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa biến thể này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td colspan="3">
                                        <strong>{{ $variants->sum(fn($v) => $v->stockItems->sum('quantity')) }} sản
                                            phẩm</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có biến thể nào</h5>
                        <p class="text-muted mb-3">Hãy thêm biến thể đầu tiên cho sản phẩm này</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#bulkCreateModal">
                                <i class="fas fa-plus-circle"></i> Tạo nhiều biến thể
                            </button>
                            <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm biến thể đơn
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bulk Create Modal -->
    <div class="modal fade" id="bulkCreateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo nhiều biến thể</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.products.variants.bulk-create', $product) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="variantsContainer">
                            <div class="variant-row mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Tên biến thể</label>
                                        <input type="text" name="variants[0][name]" class="form-control"
                                            placeholder="VD: Đỏ - Size M" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">SKU</label>
                                        <input type="text" name="variants[0][sku]" class="form-control"
                                            placeholder="VD: SP-001-RM" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Giá</label>
                                        <input type="number" name="variants[0][price]" class="form-control"
                                            value="{{ $product->price }}" min="0" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Số lượng</label>
                                        <input type="number" name="variants[0][quantity]" class="form-control"
                                            value="0" min="0">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm w-100"
                                            onclick="removeVariantRow(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addVariantRow()">
                            <i class="fas fa-plus"></i> Thêm biến thể
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Lưu tất cả
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let variantIndex = 1;

            function addVariantRow() {
                const container = document.getElementById('variantsContainer');
                const newRow = document.createElement('div');
                newRow.className = 'variant-row mb-3 p-3 border rounded';
                newRow.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Tên biến thể</label>
                <input type="text" name="variants[${variantIndex}][name]" class="form-control" placeholder="VD: Đỏ - Size M" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">SKU</label>
                <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="VD: SP-001-RM" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Giá</label>
                <input type="number" name="variants[${variantIndex}][price]" class="form-control" value="{{ $product->price }}" min="0" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Số lượng</label>
                <input type="number" name="variants[${variantIndex}][quantity]" class="form-control" value="0" min="0">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeVariantRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
                container.appendChild(newRow);
                variantIndex++;
            }

            function removeVariantRow(button) {
                const container = document.getElementById('variantsContainer');
                if (container.children.length > 1) {
                    button.closest('.variant-row').remove();
                } else {
                    alert('Phải có ít nhất 1 biến thể!');
                }
            }
        </script>
    @endpush
@endsection
