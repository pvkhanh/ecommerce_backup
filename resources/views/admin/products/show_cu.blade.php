@extends('admin.layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ $product->name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                            <li class="breadcrumb-item active">Chi tiết</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Product Info -->
            <div class="col-lg-8">
                <!-- Basic Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Tên sản phẩm:</dt>
                            <dd class="col-sm-9">{{ $product->name }}</dd>

                            <dt class="col-sm-3">Slug:</dt>
                            <dd class="col-sm-9"><code>{{ $product->slug }}</code></dd>

                            <dt class="col-sm-3">Giá:</dt>
                            <dd class="col-sm-9">
                                <strong class="text-success">{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                            </dd>

                            <dt class="col-sm-3">Trạng thái:</dt>
                            {{-- <dd class="col-sm-9">
                            <span class="badge bg-{{ $product->status->color() }}">
                                {{ $product->status->label() }}
                            </span>
                        </dd> --}}

                            <dt class="col-sm-3">Mô tả:</dt>
                            <dd class="col-sm-9">{{ $product->description ?: 'Không có mô tả' }}</dd>

                            <dt class="col-sm-3">Danh mục:</dt>
                            <dd class="col-sm-9">
                                @forelse($product->categories as $category)
                                    <span class="badge bg-secondary me-1">{{ $category->name }}</span>
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

                <!-- Images Gallery -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Hình ảnh ({{ $product->images->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if ($product->images->count() > 0)
                            <div class="row g-2">
                                @foreach ($product->images as $image)
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <div class="card {{ $image->pivot->is_main ? 'border-primary' : '' }}">
                                            <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top"
                                                style="height: 150px; object-fit: cover;" alt="{{ $image->alt_text }}">
                                            @if ($image->pivot->is_main)
                                                <div class="card-body p-2 text-center">
                                                    <span class="badge bg-primary w-100">Ảnh chính</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-images fa-3x mb-2"></i>
                                <p>Chưa có hình ảnh</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Variants -->
                @if ($product->variants->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Biến thể ({{ $product->variants->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tên biến thể</th>
                                            <th>SKU</th>
                                            <th>Giá</th>
                                            <th>Tồn kho</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->variants as $variant)
                                            <tr>
                                                <td>{{ $variant->name }}</td>
                                                <td><code>{{ $variant->sku }}</code></td>
                                                <td>{{ number_format($variant->price, 0, ',', '.') }}đ</td>
                                                <td>
                                                    @php
                                                        $totalStock = $variant->stockItems->sum('quantity');
                                                    @endphp
                                                    <span class="badge bg-{{ $totalStock > 0 ? 'success' : 'danger' }}">
                                                        {{ $totalStock }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Reviews -->
                @if ($product->reviews->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Đánh giá ({{ $product->reviews->count() }})</h5>
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
                                    <p class="mb-0">{{ $review->comment }}</p>
                                    <span
                                        class="badge bg-{{ $review->status->value == 'approved' ? 'success' : 'warning' }}">
                                        {{ $review->status->name }}
                                    </span>
                                </div>
                            @endforeach

                            @if ($product->reviews->count() > 5)
                                <div class="text-center">
                                    <small class="text-muted">Và {{ $product->reviews->count() - 5 }} đánh giá
                                        khác...</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thống kê</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Số biến thể:</span>
                                <strong>{{ $product->variants->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Tổng tồn kho:</span>
                                <strong>{{ $product->total_stock ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Đánh giá:</span>
                                <strong>
                                    <i class="fas fa-star text-warning"></i>
                                    {{ number_format($product->average_rating, 1) }}
                                    ({{ $product->review_count }})
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Giá thấp nhất:</span>
                                <strong>{{ number_format($product->min_price, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Giá cao nhất:</span>
                                <strong>{{ number_format($product->max_price, 0, ',', '.') }}đ</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thao tác nhanh</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>

                            @if ($product->status->value == 'active')
                                <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="inactive">
                                    <input type="hidden" name="name" value="{{ $product->name }}">
                                    <input type="hidden" name="price" value="{{ $product->price }}">
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-pause"></i> Ngừng bán
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
                                        <i class="fas fa-play"></i> Kích hoạt
                                    </button>
                                </form>
                            @endif

                            <a href="#" class="btn btn-info"
                                onclick="window.open('{{ url('/products/' . $product->slug) }}', '_blank')">
                                <i class="fas fa-eye"></i> Xem trên web
                            </a>

                            <button type="button" class="btn btn-secondary"
                                onclick="copyToClipboard('{{ url('/products/' . $product->slug) }}')">
                                <i class="fas fa-link"></i> Copy link
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">Vùng nguy hiểm</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            Xóa sản phẩm sẽ không thể khôi phục.
                        </p>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Xóa sản phẩm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    alert('Đã copy link sản phẩm!');
                }, function(err) {
                    alert('Không thể copy: ', err);
                });
            }
        </script>
    @endpush
@endsection
