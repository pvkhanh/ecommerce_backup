@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
    <div class="container-fluid py-4">

        {{-- ====== Breadcrumb & Header ====== --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0"><i class="fas fa-box-open me-2 text-primary"></i>Quản lý sản phẩm</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small text-muted mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sản phẩm</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button type="button" class="btn btn-secondary me-2" id="bulkActionsBtn" style="display:none;">
                    <i class="fas fa-tasks me-1"></i>Thao tác hàng loạt
                </button>
                <a href="{{ route('admin.products.trash') }}" class="btn btn-outline-danger me-2">
                    <i class="fas fa-trash-alt me-1"></i>Thùng rác
                    @if (isset($trashedCount) && $trashedCount > 0)
                        <span class="badge bg-danger ms-1">{{ $trashedCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                </a>
            </div>
        </div>

        {{-- ====== Thẻ thống kê ====== --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card info-card bg-gradient-primary text-white shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Tổng sản phẩm</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h3>
                        </div>
                        <i class="fas fa-cubes fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card info-card bg-gradient-success text-white shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Đang bán</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($activeProducts) }}</h3>
                        </div>
                        <i class="fas fa-store fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card info-card bg-gradient-warning text-white shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Hết hàng</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($outOfStock) }}</h3>
                        </div>
                        <i class="fas fa-exclamation-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card info-card bg-gradient-secondary text-white shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Đang ẩn</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($hiddenProducts) }}</h3>
                        </div>
                        <i class="fas fa-eye-slash fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== Bộ lọc nâng cao ====== --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                            placeholder="Tên, SKU, mô tả...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Tất cả --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}"
                                    {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Khoảng giá</label>
                        <input type="text" name="price_range" class="form-control" placeholder="VD: 100000-500000"
                            value="{{ request('price_range') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sắp xếp</label>
                        <select name="sort_by" class="form-select">
                            <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Giá thấp -
                                cao</option>
                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Giá cao -
                                thấp</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="sales" {{ request('sort_by') == 'sales' ? 'selected' : '' }}>Bán chạy</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ====== Bảng danh sách sản phẩm ====== --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <input type="checkbox" id="selectAll" class="form-check-input me-2">
                    <label for="selectAll" class="fw-semibold">Chọn tất cả</label>
                    <span class="text-muted ms-2" id="selectedCount">(0 mục được chọn)</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="30"></th>
                            <th width="80">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th>Ngày cập nhật</th>
                            <th class="text-center" width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input product-checkbox"
                                        value="{{ $product->id }}">
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . ($product->images->where('id', $product->primary_image_id)->first()->path ?? ($product->images->first()->path ?? 'images/default-product.png'))) }}"
                                        class="rounded shadow-sm" style="width:60px; height:60px; object-fit:cover;"
                                        loading="lazy" alt="{{ $product->name }}">
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}"
                                        class="text-decoration-none text-dark fw-semibold">
                                        {{ Str::limit($product->name, 40) }}
                                    </a>
                                    @if ($product->sku)
                                        <small class="d-block text-muted">SKU: {{ $product->sku }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->categories->count() > 0)
                                        @foreach ($product->categories->take(2) as $cat)
                                            <span class="badge bg-light text-dark">{{ $cat->name }}</span>
                                        @endforeach
                                        @if ($product->categories->count() > 2)
                                            <span
                                                class="badge bg-light text-dark">+{{ $product->categories->count() - 2 }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->sale_price && $product->sale_price < $product->price)
                                        <div>
                                            <span
                                                class="text-danger fw-bold">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                                            <small
                                                class="d-block text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }}đ</small>
                                        </div>
                                    @else
                                        <span class="fw-bold">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                    @endif
                                </td>
                                <td>
                                    @php $stock = $product->stockItems->sum('quantity'); @endphp
                                    @if ($stock > 50)
                                        <span class="badge bg-success">{{ $stock }}</span>
                                    @elseif($stock > 10)
                                        <span class="badge bg-warning">{{ $stock }}</span>
                                    @elseif($stock > 0)
                                        <span class="badge bg-danger">{{ $stock }}</span>
                                    @else
                                        <span class="badge bg-dark">0</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-status {{ $product->status->value ?? $product->status }}">
                                        {{ $product->status->label() }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $product->updated_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip"
                                            title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete({{ $product->id }})" data-bs-toggle="tooltip"
                                            title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        class="d-none" id="deleteForm{{ $product->id }}">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                    <p class="mb-0">Không có sản phẩm nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}
                    trong tổng số {{ $products->total() }} sản phẩm
                </div>
                {{ $products->links('components.pagination') }}
            </div>
        </div>

    </div>
    @push('styles')
        <style>
            /* Wrapper cho table scroll */
            .table-responsive {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                /* scroll mượt trên iOS */
                scrollbar-width: thin;
                /* Firefox */
                scrollbar-color: rgba(79, 70, 229, 0.5) rgba(229, 231, 235, 0.3);
                /* Firefox */
            }

            /* Custom scroll bar cho Chrome, Edge, Safari */
            .table-responsive::-webkit-scrollbar {
                height: 8px;
            }

            .table-responsive::-webkit-scrollbar-track {
                background: rgba(229, 231, 235, 0.3);
                border-radius: 8px;
            }

            .table-responsive::-webkit-scrollbar-thumb {
                background: rgba(79, 70, 229, 0.5);
                border-radius: 8px;
            }

            /* Giữ table full width và nowrap các cột */
            .table-responsive table {
                min-width: 600px;
                /* có thể tăng nếu cần nhiều cột */
                table-layout: auto;
            }

            /* Responsive nhỏ */
            @media (max-width: 768px) {
                .table-responsive table {
                    font-size: 0.85rem;
                }

                thead th,
                tbody td {
                    padding: 10px 12px;
                    white-space: nowrap;
                    /* tránh wrap */
                }
            }

            /* Category Badges */
            .badge.bg-light {
                background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%) !important;
                color: #4338ca;
                padding: 6px 12px;
                border-radius: 8px;
                font-weight: 600;
                margin: 2px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            }

            .badge.bg-light:nth-child(2) {
                background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%) !important;
                color: #166534;
            }

            /* Status Badges */
            .badge-status {
                display: inline-block;
                padding: 0.5rem 1rem;
                border-radius: 999px;
                font-weight: 700;
                font-size: 0.85rem;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                white-space: nowrap;
            }

            .badge-status.active,
            .badge-status.published {
                background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
                color: white;
            }

            .badge-status.inactive,
            .badge-status.hidden {
                background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%);
                color: white;
            }

            .badge-status.pending,
            .badge-status.draft {
                background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
                color: white;
            }

            .badge-status.out_of_stock {
                background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
                color: #1e293b;
            }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Copy to clipboard
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Đã copy link sản phẩm!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }, function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể copy: ' + err,
                        confirmButtonColor: '#4f46e5'
                    });
                });
            }

            // Confirm delete
            function confirmDelete() {
                Swal.fire({
                    title: 'Xác nhận xóa?',
                    html: 'Bạn có chắc chắn muốn xóa sản phẩm "<strong>{{ $product->name }}</strong>"?<br><small class="text-danger">Hành động này không thể hoàn tác!</small>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-trash me-2"></i>Xóa vĩnh viễn',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Hủy',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').submit();
                    }
                });
            }

            // Show image preview
            function showImagePreview(imageUrl) {
                Swal.fire({
                    imageUrl: imageUrl,
                    imageAlt: 'Product Image',
                    showConfirmButton: false,
                    showCloseButton: true,
                    background: 'transparent',
                    backdrop: 'rgba(0,0,0,0.8)',
                    customClass: {
                        image: 'img-fluid rounded shadow-lg'
                    },
                    width: 'auto',
                    padding: '2rem'
                });
            }

            // Initialize tooltips
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });

            // Success message
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        </script>
    @endpush
@endsection
