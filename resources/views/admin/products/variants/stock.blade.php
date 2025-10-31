{{-- @extends('admin.layouts.app') --}}
@extends('layouts.admin')
@section('title', 'Quản lý tồn kho')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h1 class="h3 mb-0">Quản lý tồn kho: {{ $variant->name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.variants.index', $product) }}">Biến thể</a>
                    </li>
                    <li class="breadcrumb-item active">Quản lý kho</li>
                </ol>
            </nav>
        </div>

        {{-- @if (session('success'))
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
        @endif --}}

        <div class="row">
            <div class="col-lg-8">
                <!-- Current Stock -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tồn kho hiện tại</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addStockModal">
                            <i class="fas fa-plus"></i> Thêm vị trí kho mới
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($variant->stockItems->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Vị trí kho</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th style="width: 250px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($variant->stockItems as $stock)
                                            <tr>
                                                <td>
                                                    <i class="fas fa-warehouse text-primary me-2"></i>
                                                    <strong>{{ $stock->location }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-{{ $stock->quantity > 10 ? 'success' : ($stock->quantity > 0 ? 'warning' : 'danger') }} fs-6">
                                                        {{ $stock->quantity }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($stock->quantity > 10)
                                                        <span class="badge bg-success">Đủ hàng</span>
                                                    @elseif($stock->quantity > 0)
                                                        <span class="badge bg-warning">Sắp hết</span>
                                                    @else
                                                        <span class="badge bg-danger">Hết hàng</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-success"
                                                            onclick="openStockModal('{{ $stock->location }}', {{ $stock->quantity }}, 'increase')">
                                                            <i class="fas fa-plus"></i> Nhập
                                                        </button>
                                                        <button type="button" class="btn btn-outline-warning"
                                                            onclick="openStockModal('{{ $stock->location }}', {{ $stock->quantity }}, 'decrease')">
                                                            <i class="fas fa-minus"></i> Xuất
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary"
                                                            onclick="openStockModal('{{ $stock->location }}', {{ $stock->quantity }}, 'set')">
                                                            <i class="fas fa-edit"></i> Sửa
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td><strong>Tổng tồn kho:</strong></td>
                                            <td class="text-center">
                                                <strong
                                                    class="fs-5 text-primary">{{ $variant->stockItems->sum('quantity') }}</strong>
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-boxes fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có tồn kho</h5>
                                <p class="text-muted mb-3">Hãy thêm vị trí kho đầu tiên</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addStockModal">
                                    <i class="fas fa-plus"></i> Thêm vị trí kho
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thao tác nhanh</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-success w-100"
                                    onclick="openQuickStockModal('increase')">
                                    <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                                    Nhập hàng nhanh
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-warning w-100"
                                    onclick="openQuickStockModal('decrease')">
                                    <i class="fas fa-minus-circle fa-2x d-block mb-2"></i>
                                    Xuất hàng nhanh
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary w-100" onclick="openQuickStockModal('set')">
                                    <i class="fas fa-edit fa-2x d-block mb-2"></i>
                                    Đặt số lượng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin biến thể</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Sản phẩm:</dt>
                            <dd class="col-sm-7">{{ $product->name }}</dd>

                            <dt class="col-sm-5">Biến thể:</dt>
                            <dd class="col-sm-7">{{ $variant->name }}</dd>

                            <dt class="col-sm-5">SKU:</dt>
                            <dd class="col-sm-7"><code>{{ $variant->sku }}</code></dd>

                            <dt class="col-sm-5">Giá:</dt>
                            <dd class="col-sm-7">{{ number_format($variant->price, 0, ',', '.') }}đ</dd>

                            <dt class="col-sm-5">Tồn kho:</dt>
                            <dd class="col-sm-7">
                                <strong class="text-primary fs-5">{{ $variant->stockItems->sum('quantity') }}</strong>
                            </dd>
                        </dl>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Phân bổ kho</h5>
                    </div>
                    <div class="card-body">
                        @if ($variant->stockItems->count() > 0)
                            <div class="mb-3">
                                @php
                                    $total = $variant->stockItems->sum('quantity');
                                @endphp
                                @foreach ($variant->stockItems as $stock)
                                    @php
                                        $percentage = $total > 0 ? ($stock->quantity / $total) * 100 : 0;
                                    @endphp
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>{{ $stock->location }}</small>
                                            <small><strong>{{ $stock->quantity }}</strong>
                                                ({{ number_format($percentage, 1) }}%)</small>
                                        </div>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $stock->quantity > 10 ? 'success' : ($stock->quantity > 0 ? 'warning' : 'danger') }}"
                                                style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small mb-0">Chưa có dữ liệu phân bổ</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Stock Location Modal -->
    <div class="modal fade" id="addStockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.products.variants.update-stock', [$product, $variant]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm vị trí kho mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="set">

                        <div class="mb-3">
                            <label class="form-label">Tên vị trí kho <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control"
                                placeholder="VD: Kho A, Kệ 1, v.v." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số lượng ban đầu</label>
                            <input type="number" name="quantity" class="form-control" value="0" min="0"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Stock Modal -->
    <div class="modal fade" id="updateStockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.products.variants.update-stock', [$product, $variant]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateStockModalTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="location" id="updateLocation">
                        <input type="hidden" name="action" id="updateAction">

                        <div class="alert alert-info" id="currentStockInfo"></div>

                        <div class="mb-3">
                            <label class="form-label" id="quantityLabel">Số lượng</label>
                            <input type="number" name="quantity" id="updateQuantity" class="form-control"
                                min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" id="updateStockBtn">
                            <i class="fas fa-save"></i> Xác nhận
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openStockModal(location, currentQty, action) {
                document.getElementById('updateLocation').value = location;
                document.getElementById('updateAction').value = action;
                document.getElementById('updateQuantity').value = '';

                const modal = new bootstrap.Modal(document.getElementById('updateStockModal'));
                const titleEl = document.getElementById('updateStockModalTitle');
                const infoEl = document.getElementById('currentStockInfo');
                const labelEl = document.getElementById('quantityLabel');
                const btnEl = document.getElementById('updateStockBtn');

                switch (action) {
                    case 'increase':
                        titleEl.textContent = 'Nhập hàng - ' + location;
                        infoEl.innerHTML = '<strong>Tồn kho hiện tại:</strong> ' + currentQty;
                        labelEl.textContent = 'Số lượng nhập';
                        btnEl.className = 'btn btn-success';
                        btnEl.innerHTML = '<i class="fas fa-plus"></i> Nhập hàng';
                        break;
                    case 'decrease':
                        titleEl.textContent = 'Xuất hàng - ' + location;
                        infoEl.innerHTML = '<strong>Tồn kho hiện tại:</strong> ' + currentQty;
                        labelEl.textContent = 'Số lượng xuất';
                        btnEl.className = 'btn btn-warning';
                        btnEl.innerHTML = '<i class="fas fa-minus"></i> Xuất hàng';
                        break;
                    case 'set':
                        titleEl.textContent = 'Đặt số lượng - ' + location;
                        infoEl.innerHTML = '<strong>Tồn kho hiện tại:</strong> ' + currentQty;
                        labelEl.textContent = 'Số lượng mới';
                        btnEl.className = 'btn btn-primary';
                        btnEl.innerHTML = '<i class="fas fa-save"></i> Cập nhật';
                        document.getElementById('updateQuantity').value = currentQty;
                        break;
                }

                modal.show();
            }

            function openQuickStockModal(action) {
                if (!{{ $variant->stockItems->count() }}) {
                    alert('Vui lòng thêm vị trí kho trước!');
                    return;
                }
                const location = '{{ $variant->stockItems->first()->location ?? 'default' }}';
                const currentQty = {{ $variant->stockItems->first()->quantity ?? 0 }};
                openStockModal(location, currentQty, action);
            }
        </script>
    @endpush
@endsection
