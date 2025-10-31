{{-- @extends('admin.layouts.app')
@extends('layouts.admin')
@section('title', 'Quản lý sản phẩm')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Quản lý sản phẩm</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm mới
            </a>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}"
                                    {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên, slug, mô tả..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                </form>
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

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Đánh giá</th>
                                <th style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        @php
                                            $primaryImage = $product->images->where('pivot.is_main', true)->first();
                                        @endphp
                                        @if ($primaryImage)
                                            <img src="{{ asset('storage/' . $primaryImage->path) }}" class="img-thumbnail"
                                                style="width: 60px; height: 60px; object-fit: cover;"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $product->slug }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach ($product->categories->take(2) as $category)
                                            <span class="badge bg-secondary">{{ $category->name }}</span>
                                        @endforeach
                                        @if ($product->categories->count() > 2)
                                            <span
                                                class="badge bg-light text-dark">+{{ $product->categories->count() - 2 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                                        @if ($product->variants->count() > 0)
                                            <br>
                                            <small class="text-muted">{{ $product->variants->count() }} biến thể</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match ($product->status->value) {
                                                'active' => 'success',
                                                'inactive' => 'secondary',
                                                'draft' => 'warning',
                                                'out_of_stock' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ $product->status->name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($product->reviews_count > 0)
                                            <i class="fas fa-star text-warning"></i>
                                            <strong>{{ number_format($product->average_rating, 1) }}</strong>
                                            <small class="text-muted">({{ $product->reviews_count }})</small>
                                        @else
                                            <small class="text-muted">Chưa có</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                                class="btn btn-outline-info" title="Xem">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="btn btn-outline-primary" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Không có sản phẩm nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $products->links('components.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection --}}









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
                {{-- Nút thùng rác --}}
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
                                    {{-- <img src="{{ asset(
                                        'storage/' .
                                            ($product->images->where('id', $product->primary_image_id)->first()->path ??
                                                ($product->images->first()->path ?? 'images/default-product.png')),
                                    ) }}"
                                        class="rounded shadow-sm" style="width:70px; height:70px; object-fit:cover;"> --}}


                                    {{-- Cách 1: Nếu primary_image_id là id của bảng images
                                    @php
                                        // Lấy ảnh chính theo primary_image_id
                                        $primaryImage = $product->images->firstWhere('id', $product->primary_image_id);

                                        // Nếu không có ảnh chính, lấy ảnh đầu tiên
                                        if (!$primaryImage) {
                                            $primaryImage = $product->images->first();
                                        }

                                        $imgPath = $primaryImage->path ?? 'images/default-product.png';
                                    @endphp

                                    <img src="{{ asset('storage/' . $imgPath) }}" class="rounded shadow-sm"
                                        style="width:70px; height:70px; object-fit:cover;"> --}}


                                    {{-- Cách 2: Nếu primary_image_id được lưu trong pivot table (is_main)✅
                                    Điểm mấu chốt:
                                    Nếu bạn dùng pivot field is_main thì phải check pivot.is_main.
                                    Nếu bạn dùng primary_image_id lưu trực tiếp ở bảng products thì check id của collection. --}}
                                    @php
                                        // Lấy ảnh chính dựa vào bảng pivot
                                        $primaryImage = $product->images->firstWhere('pivot.is_main', true);

                                        // Nếu không có, lấy ảnh đầu tiên
                                        if (!$primaryImage) {
                                            $primaryImage = $product->images->first();
                                        }

                                        // Nếu vẫn không có ảnh, dùng ảnh mặc định
                                        $imgPath = $primaryImage->path ?? 'images/default-product.png';
                                    @endphp

                                    <img src="{{ asset('storage/' . $imgPath) }}" class="rounded shadow-sm"
                                        style="width:70px; height:70px; object-fit:cover;">


                                    {{-- @php
                                        $img =
                                            $product->images->where('id', $product->primary_image_id)->first() ??
                                            $product->images->first();
                                    @endphp

                                    @if ($img)
                                        <img src="{{ asset('storage/' . $img->path) }}" class="rounded shadow-sm"
                                            style="width:70px;height:70px;object-fit:cover;">
                                    @else
                                        <img src="{{ asset('images/default-product.png') }}" class="rounded shadow-sm"
                                            style="width:70px;height:70px;object-fit:cover;">
                                    @endif --}}

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
                                    @php
                                        $stock = $product->stockItems->sum('quantity');
                                    @endphp
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

    {{-- Modal thao tác hàng loạt --}}
    <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thao tác hàng loạt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bulkActionForm">
                        @csrf
                        <input type="hidden" name="ids" id="bulkIds">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chọn hành động</label>
                            <select class="form-select" id="bulkAction" required>
                                <option value="">-- Chọn --</option>
                                <option value="update_status">Cập nhật trạng thái</option>
                                <option value="delete">Xóa sản phẩm</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="statusSelectDiv">
                            <label class="form-label fw-semibold">Trạng thái mới</label>
                            <select class="form-select" name="status" id="newStatus">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-warning d-none" id="deleteWarning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Bạn có chắc muốn xóa <strong><span id="deleteCount"></span></strong> sản phẩm đã chọn?
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="executeBulkAction">Thực hiện</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Xử lý checkbox
            const selectAllCheckbox = document.getElementById('selectAll');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            const selectedCountSpan = document.getElementById('selectedCount');
            const bulkActionsBtn = document.getElementById('bulkActionsBtn');

            function updateSelectedCount() {
                const checked = document.querySelectorAll('.product-checkbox:checked').length;
                const total = productCheckboxes.length;

                selectedCountSpan.textContent = `(${checked}/${total} mục được chọn)`;
                bulkActionsBtn.style.display = checked > 0 ? 'inline-block' : 'none';

                if (checked === total && total > 0) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (checked > 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }

            selectAllCheckbox.addEventListener('change', function() {
                productCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                    const row = cb.closest('tr');
                    if (this.checked) {
                        row.classList.add('table-active');
                    } else {
                        row.classList.remove('table-active');
                    }
                });
                updateSelectedCount();
            });

            productCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateSelectedCount();
                    const row = this.closest('tr');
                    if (this.checked) {
                        row.classList.add('table-active');
                    } else {
                        row.classList.remove('table-active');
                    }
                });
            });

            // Mở modal bulk actions
            bulkActionsBtn.addEventListener('click', function() {
                const checkedIds = Array.from(document.querySelectorAll('.product-checkbox:checked'))
                    .map(cb => cb.value);

                document.getElementById('bulkIds').value = checkedIds.join(',');
                document.getElementById('deleteCount').textContent = checkedIds.length;

                const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
                modal.show();
            });

            // Xử lý thay đổi action
            document.getElementById('bulkAction').addEventListener('change', function() {
                const statusDiv = document.getElementById('statusSelectDiv');
                const deleteWarning = document.getElementById('deleteWarning');

                statusDiv.classList.add('d-none');
                deleteWarning.classList.add('d-none');

                if (this.value === 'update_status') {
                    statusDiv.classList.remove('d-none');
                } else if (this.value === 'delete') {
                    deleteWarning.classList.remove('d-none');
                }
            });

            // Thực hiện bulk action
            document.getElementById('executeBulkAction').addEventListener('click', function() {
                const action = document.getElementById('bulkAction').value;
                const ids = document.getElementById('bulkIds').value.split(',');

                if (!action) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng chọn hành động',
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }

                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';

                let url, data;

                if (action === 'update_status') {
                    url = '{{ route('admin.products.bulk-update-status') }}';
                    data = {
                        ids: ids,
                        status: document.getElementById('newStatus').value,
                        _token: '{{ csrf_token() }}'
                    };
                } else if (action === 'delete') {
                    url = '{{ route('admin.products.bulk-delete') }}';
                    data = {
                        ids: ids,
                        _token: '{{ csrf_token() }}'
                    };
                }

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: data.message,
                                confirmButtonColor: '#4f46e5',
                                timer: 2000
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: data.message,
                                confirmButtonColor: '#4f46e5'
                            });
                            btn.disabled = false;
                            btn.innerHTML = 'Thực hiện';
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Có lỗi xảy ra khi xử lý',
                            confirmButtonColor: '#4f46e5'
                        });
                        btn.disabled = false;
                        btn.innerHTML = 'Thực hiện';
                    });
            });


            // fetch(url, {
            //         method: 'POST',
            //         body: (() => {
            //             let formData = new FormData();
            //             formData.append('_token', '{{ csrf_token() }}');

            //             // Nếu data có thuộc tính ids
            //             if (data.ids && Array.isArray(data.ids)) {
            //                 data.ids.forEach(id => formData.append('ids[]', id));
            //             }

            //             // Nếu có status (áp dụng cho bulkUpdateStatus)
            //             if (data.status) {
            //                 formData.append('status', data.status);
            //             }

            //             return formData;
            //         })()
            //     })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.success) {
            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Thành công!',
            //                 text: data.message ?? 'Thao tác đã được thực hiện thành công!',
            //                 confirmButtonColor: '#4f46e5',
            //                 timer: 2000
            //             }).then(() => location.reload());
            //         } else {
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Lỗi',
            //                 text: data.message ?? 'Không thể thực hiện thao tác này.',
            //                 confirmButtonColor: '#4f46e5'
            //             });
            //             btn.disabled = false;
            //             btn.innerHTML = 'Thực hiện';
            //         }
            //     })
            //     .catch(error => {
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Lỗi',
            //             text: 'Có lỗi xảy ra khi xử lý!',
            //             confirmButtonColor: '#4f46e5'
            //         });
            //         btn.disabled = false;
            //         btn.innerHTML = 'Thực hiện';
            //     });


            // Xác nhận xóa đơn lẻ
            function confirmDelete(id) {
                Swal.fire({
                    title: "Xác nhận xóa?",
                    text: "Sản phẩm này sẽ được đưa vào thùng rác!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#ef4444",
                    cancelButtonColor: "#64748b",
                    confirmButtonText: '<i class="fas fa-trash me-2"></i>Xóa',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Hủy',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm' + id).submit();
                    }
                });
            }

            // Toast thông báo
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

            // Tooltip
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                updateSelectedCount();
            });
        </script>
    @endpush

    @push('styles')
        <style>
            /* Font & Typography */
            body,
            table {
                font-family: 'Inter', 'Roboto', sans-serif;
                font-size: 15px;
                color: #1e293b;
            }

            /* Info Cards */
            .info-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                border-radius: 12px;
                overflow: hidden;
            }

            .info-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            .bg-gradient-primary {
                background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%);
            }

            .bg-gradient-success {
                background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            }

            .bg-gradient-warning {
                background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            }

            .bg-gradient-secondary {
                background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%);
            }

            /* Table */
            table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            thead {
                background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                color: #475569;
                font-size: 0.85rem;
            }

            thead th {
                padding: 14px 16px;
                border-bottom: 2px solid #e2e8f0;
            }

            tbody td {
                padding: 14px 16px;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }

            tbody tr {
                transition: all 0.2s ease;
            }

            tbody tr:hover {
                background-color: #f8fafc;
                transform: translateX(2px);
            }

            tbody tr.table-active {
                background-color: #eff6ff;
                border-left: 3px solid #4f46e5;
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

            /* Buttons */
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

            /* Card */
            .card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            }

            .card-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-bottom: 2px solid #e2e8f0;
                border-radius: 12px 12px 0 0 !important;
            }

            /* Form Controls */
            .form-control,
            .form-select {
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                transition: all 0.2s ease;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }

            /* Modal */
            .modal-content {
                border-radius: 16px;
                border: none;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            }

            .modal-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-bottom: 2px solid #e2e8f0;
                border-radius: 16px 16px 0 0;
            }

            /* Pagination */
            .pagination {
                gap: 0.5rem;
            }

            .page-link {
                border-radius: 8px;
                border: 2px solid #e2e8f0;
                color: #475569;
                font-weight: 600;
                transition: all 0.2s ease;
            }

            .page-link:hover {
                background: #4f46e5;
                color: white;
                border-color: #4f46e5;
            }

            .page-item.active .page-link {
                background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%);
                border-color: #4f46e5;
            }

            /* Animations */
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

            /* Responsive
                                                                                                                                                    @media (max-width: 768px) {
                                                                                                                                                        .table {
                                                                                                                                                            font-size: 0.85rem;
                                                                                                                                                        }

                                                                                                                                                        thead th,
                                                                                                                                                        tbody td {
                                                                                                                                                            padding: 10px 12px;
                                                                                                                                                        }
                                                                                                                                                    } */
        </style>
    @endpush
@endsection
