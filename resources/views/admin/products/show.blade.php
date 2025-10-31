@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <div class="container-fluid py-4">

        {{-- Breadcrumb & Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0"><i class="fas fa-box me-2 text-primary"></i>{{ $product->name }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small text-muted mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Left Column --}}
            <div class="col-lg-8">
                {{-- Thông tin cơ bản --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-3">Tên sản phẩm:</dt>
                            <dd class="col-sm-9">{{ $product->name }}</dd>

                            <dt class="col-sm-3">Slug:</dt>
                            <dd class="col-sm-9"><code>{{ $product->slug }}</code></dd>

                            @if($product->sku)
                            <dt class="col-sm-3">SKU:</dt>
                            <dd class="col-sm-9"><code>{{ $product->sku }}</code></dd>
                            @endif

                            <dt class="col-sm-3">Giá:</dt>
                            <dd class="col-sm-9">
                                @if ($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-danger fw-bold fs-5">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                                    <small class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 0, ',', '.') }}đ</small>
                                    <span class="badge bg-danger ms-2">Giảm {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</span>
                                @else
                                    <strong class="text-success fs-5">{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                                @endif
                            </dd>

                            <dt class="col-sm-3">Trạng thái:</dt>
                            <dd class="col-sm-9">
                                <span class="badge-status {{ $product->status->value }}">
                                    {{ $product->status->label() }}
                                </span>
                            </dd>

                            @if($product->is_featured)
                            <dt class="col-sm-3">Nổi bật:</dt>
                            <dd class="col-sm-9">
                                <span class="badge bg-warning"><i class="fas fa-star me-1"></i>Sản phẩm nổi bật</span>
                            </dd>
                            @endif

                            <dt class="col-sm-3">Mô tả:</dt>
                            <dd class="col-sm-9">{{ $product->description ?: 'Không có mô tả' }}</dd>

                            <dt class="col-sm-3">Danh mục:</dt>
                            <dd class="col-sm-9">
                                @forelse($product->categories as $category)
                                    <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                                @empty
                                    <span class="text-muted">Chưa phân loại</span>
                                @endforelse
                            </dd>

                            <dt class="col-sm-3">Ngày tạo:</dt>
                            <dd class="col-sm-9">{{ $product->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-3">Cập nhật:</dt>
                            <dd class="col-sm-9">{{ $product->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>

                {{-- Hình ảnh --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Hình ảnh ({{ $product->images->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if ($product->images->count() > 0)
                            <div class="row g-3">
                                @foreach ($product->images as $image)
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <div class="card image-card {{ $image->pivot->is_main ? 'border-primary border-3' : '' }}">
                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                class="card-img-top image-preview"
                                                style="height: 150px; object-fit: cover; cursor: pointer;"
                                                alt="{{ $image->alt_text }}"
                                                onclick="showImagePreview('{{ asset('storage/' . $image->path) }}')">
                                            @if ($image->pivot->is_main)
                                                <div class="card-body p-2 text-center">
                                                    <span class="badge bg-primary w-100"><i class="fas fa-star me-1"></i>Ảnh chính</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-images fa-3x mb-3 d-block opacity-25"></i>
                                <p>Chưa có hình ảnh</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Biến thể --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Biến thể ({{ $product->variants->count() }})</h5>
                        @if(Route::has('admin.products.variants.index'))
                        <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-cog me-1"></i>Quản lý biến thể
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($product->variants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tên biến thể</th>
                                            <th>SKU</th>
                                            <th>Giá</th>
                                            <th>Tồn kho</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->variants as $variant)
                                            <tr>
                                                <td>{{ $variant->name }}</td>
                                                <td><code>{{ $variant->sku }}</code></td>
                                                <td class="fw-bold">{{ number_format($variant->price, 0, ',', '.') }}đ</td>
                                                <td>
                                                    @php
                                                        $totalStock = $variant->stockItems->sum('quantity');
                                                    @endphp
                                                    @if ($totalStock > 50)
                                                        <span class="badge bg-success">{{ $totalStock }}</span>
                                                    @elseif($totalStock > 10)
                                                        <span class="badge bg-warning">{{ $totalStock }}</span>
                                                    @elseif($totalStock > 0)
                                                        <span class="badge bg-danger">{{ $totalStock }}</span>
                                                    @else
                                                        <span class="badge bg-dark">0</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(Route::has('admin.products.variants.stock'))
                                                    <a href="{{ route('admin.products.variants.stock', [$product, $variant]) }}"
                                                        class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Quản lý kho">
                                                        <i class="fas fa-boxes"></i>
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(Route::has('admin.products.variants.index'))
                            <div class="mt-3 text-center">
                                <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-primary">
                                    <i class="fas fa-cog me-2"></i>Quản lý tất cả biến thể
                                </a>
                            </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                <h6 class="text-muted">Sản phẩm chưa có biến thể</h6>
                                <p class="text-muted small mb-3">Thêm biến thể nếu sản phẩm có nhiều phiên bản (màu sắc, kích thước,...)</p>
                                @if(Route::has('admin.products.variants.create'))
                                <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Thêm biến thể
                                </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Đánh giá --}}
                @if ($product->reviews->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Đánh giá ({{ $product->reviews->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($product->reviews->take(5) as $review)
                                <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong>{{ $review->user->username ?? 'Khách hàng' }}</strong>
                                            <div class="text-warning">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <p class="mb-2">{{ $review->comment }}</p>
                                    <span class="badge bg-{{ $review->status->value == 'approved' ? 'success' : 'warning' }}">
                                        {{ $review->status->label() }}
                                    </span>
                                </div>
                            @endforeach

                            @if ($product->reviews->count() > 5)
                                <div class="text-center">
                                    <small class="text-muted">Và {{ $product->reviews->count() - 5 }} đánh giá khác...</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column - Sidebar --}}
            <div class="col-lg-4">
                {{-- Thống kê --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Thống kê</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-layer-group text-primary me-2"></i>Số biến thể:</span>
                                <strong>{{ $product->variants->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-boxes text-success me-2"></i>Tổng tồn kho:</span>
                                <strong>{{ $product->total_stock ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-star text-warning me-2"></i>Đánh giá:</span>
                                <strong>
                                    {{ number_format($product->average_rating, 1) }}
                                    ({{ $product->review_count }})
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-arrow-down text-info me-2"></i>Giá thấp nhất:</span>
                                <strong>{{ number_format($product->min_price, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-arrow-up text-danger me-2"></i>Giá cao nhất:</span>
                                <strong>{{ number_format($product->max_price, 0, ',', '.') }}đ</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thao tác nhanh --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa
                            </a>

                            @if ($product->status->value == 'active')
                                <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="inactive">
                                    <input type="hidden" name="name" value="{{ $product->name }}">
                                    <input type="hidden" name="price" value="{{ $product->price }}">
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-pause me-2"></i>Ngừng bán
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="active">
                                    <input type="hidden" name="name" value="{{ $product->name }}">
                                    <input type="hidden" name="price" value="{{ $product->price }}">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-play me-2"></i>Kích hoạt
                                    </button>
                                </form>
                            @endif

                            <button type="button" class="btn btn-secondary"
                                onclick="copyToClipboard('{{ url('/products/' . $product->slug) }}')">
                                <i class="fas fa-link me-2"></i>Copy link
                            </button>

                            <a href="{{ url('/products/' . $product->slug) }}" target="_blank" class="btn btn-info">
                                <i class="fas fa-external-link-alt me-2"></i>Xem trên web
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Vùng nguy hiểm --}}
                <div class="card border-danger border-2 shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Vùng nguy hiểm</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            Xóa sản phẩm sẽ không thể khôi phục. Tất cả dữ liệu liên quan (biến thể, đánh giá, ảnh...) sẽ bị xóa.
                        </p>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                            id="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger w-100" onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>Xóa sản phẩm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    title: '{{ session("success") }}',
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
                    title: '{{ session("error") }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        </script>
    @endpush

    @push('styles')
        <style>
            .card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
                margin-bottom: 1.5rem;
            }

            .card-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-bottom: 2px solid #e2e8f0;
                border-radius: 12px 12px 0 0 !important;
            }

            .btn {
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.2s ease;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .btn-primary {
                background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%);
                border: none;
            }

            .btn-info {
                background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
                border: none;
                color: white;
            }

            .image-card {
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
            }

            .image-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            .image-preview {
                transition: all 0.3s ease;
            }

            .image-preview:hover {
                transform: scale(1.05);
            }

            .badge-status {
                display: inline-block;
                padding: 0.5rem 1rem;
                border-radius: 999px;
                font-weight: 700;
                font-size: 0.85rem;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }

            .badge-status.active, .badge-status.published {
                background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
                color: white;
            }

            .badge-status.inactive, .badge-status.hidden {
                background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%);
                color: white;
            }

            .badge-status.pending, .badge-status.draft {
                background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
                color: white;
            }

            .badge-status.out_of_stock {
                background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
                color: #1e293b;
            }

            .badge.bg-light {
                background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%) !important;
                color: #4338ca;
                padding: 6px 12px;
                border-radius: 8px;
                font-weight: 600;
            }

            .table {
                border-collapse: separate;
                border-spacing: 0;
            }

            .table thead {
                background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            }

            .table thead th {
                border-bottom: 2px solid #e2e8f0;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.85rem;
                color: #475569;
            }

            .table tbody tr {
                transition: all 0.2s ease;
            }

            .table-hover tbody tr:hover {
                background-color: #f8fafc;
                transform: translateX(2px);
            }

            dl.row dt {
                color: #64748b;
                font-weight: 600;
            }

            dl.row dd {
                color: #1e293b;
            }

            .breadcrumb {
                background: transparent;
                padding: 0;
            }

            .breadcrumb-item a {
                color: #4f46e5;
                text-decoration: none;
                font-weight: 500;
            }

            .breadcrumb-item a:hover {
                color: #6d28d9;
                text-decoration: underline;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card {
                animation: fadeIn 0.5s ease;
            }
        </style>
    @endpush
@endsection




