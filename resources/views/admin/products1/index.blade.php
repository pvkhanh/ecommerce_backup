{{-- @extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
    <div class="container-fluid px-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Quản lý sản phẩm
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sản phẩm</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fa-solid fa-plus me-2"></i>Thêm sản phẩm
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            @php
                $stats = [
                    [
                        'title' => 'Tổng sản phẩm',
                        'value' => $products->total(),
                        'icon' => 'fa-boxes',
                        'bg' => 'primary',
                    ],
                    ['title' => 'Còn hàng', 'value' => $inStockCount, 'icon' => 'fa-check', 'bg' => 'success'],
                    [
                        'title' => 'Hết hàng',
                        'value' => $outOfStockCount,
                        'icon' => 'fa-triangle-exclamation',
                        'bg' => 'warning',
                    ],
                    [
                        'title' => 'Tổng biến thể',
                        'value' => $variantsCount,
                        'icon' => 'fa-layer-group',
                        'bg' => 'danger',
                    ],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div class="col-xl-3 col-md-6">
                    <div
                        class="card shadow-sm h-100 border-0 bg-gradient-{{ $stat['bg'] }} text-white position-relative overflow-hidden">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">{{ $stat['title'] }}</h6>
                                <h2 class="fw-bold mb-0">{{ $stat['value'] }}</h2>
                            </div>
                            <div class="fs-1 opacity-25"><i class="fa-solid {{ $stat['icon'] }}"></i></div>
                        </div>
                        <div class="position-absolute top-0 end-0 opacity-10" style="font-size:6rem;">
                            <i class="fa-solid {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filter -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-filter text-primary me-2"></i>Bộ lọc nâng cao</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control form-control-lg"
                            placeholder="Tên sản phẩm..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status"
                            class="form-select form-select-lg @if (request('status') != '') border-primary border-2 @endif">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Vô hiệu hóa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select name="category_id"
                            class="form-select form-select-lg @if (request('category_id')) border-primary border-2 @endif">
                            <option value="">Tất cả</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg flex-fill"><i
                                class="fa-solid fa-filter me-2"></i>Lọc</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-lg flex-fill"><i
                                class="fa-solid fa-rotate-right me-2"></i>Đặt lại</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Trạng thái</th>
                                <th>Kho</th>
                                <th>Giá</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr class="border-bottom align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="d-flex align-items-center gap-3 text-start">
                                        @php $image = optional($product->images()->wherePivot('is_main',true)->first())->path; @endphp
                                        @if ($image)
                                            <img src="{{ asset('storage/' . $image) }}" class="rounded shadow-sm"
                                                style="width:50px;height:50px;object-fit:cover">
                                        @else
                                            <div class="rounded bg-gradient-primary text-white d-flex justify-content-center align-items-center shadow-sm fw-bold"
                                                style="width:50px;height:50px;font-size:20px;">
                                                {{ strtoupper(substr($product->name ?? 'P', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="text-start">
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <div class="text-muted small">{{ $product->sku }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach ($product->categories as $category)
                                            <span class="badge bg-info me-1">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-sm {{ $product->status ? 'btn-success' : 'btn-secondary' }} toggle-status"
                                            data-id="{{ $product->id }}">
                                            {{ $product->status ? 'Hoạt động' : 'Vô hiệu' }}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-sm {{ $product->total_stock > 0 ? 'btn-primary' : 'btn-danger' }} toggle-stock"
                                            data-id="{{ $product->id }}">
                                            {{ $product->total_stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                        </button>
                                    </td>
                                    <td class="fw-semibold">{{ number_format($product->price, 0, ',', '.') }}₫</td>
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products.show', $product->id) }}"
                                                class="btn btn-outline-info btn-sm"><i class="fa-solid fa-eye"></i></a>
                                            {{-- <a href="javascript:;" class="btn btn-outline-info btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $product->id }}"
                                                {{-- title="Xem nhanh"><i class="fa-solid fa-eye"></i></a> --}}

{{-- <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn btn-outline-warning btn-sm"><i class="fa-solid fa-pen"></i></a>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-5 text-muted text-center">
                                        <i class="fa-solid fa-inbox fs-1 mb-3 opacity-25"></i>
                                        <div>Không có sản phẩm nào</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table> --}}
<!-- Thêm sau phần table trong Products Table -->
{{-- @foreach ($products as $product)
                        <div class="modal fade" id="quickViewModal{{ $product->id }}" tabindex="-1"
                            aria-labelledby="quickViewLabel{{ $product->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="quickViewLabel{{ $product->id }}">
                                            {{ $product->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body row">
                                        <div class="col-md-5">
                                            @php $mainImage = optional($product->images()->wherePivot('is_main',true)->first())->path; @endphp
                                            @if ($mainImage)
                                                <img src="{{ asset('storage/' . $mainImage) }}"
                                                    class="img-fluid rounded">
                                            @else
                                                <div class="rounded bg-gradient-primary text-white d-flex justify-content-center align-items-center shadow-sm fw-bold"
                                                    style="width:100%;height:250px;font-size:40px;">
                                                    {{ strtoupper(substr($product->name ?? 'P', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-7">
                                            <h4 class="fw-bold">{{ number_format($product->price, 0, ',', '.') }}₫</h4>
                                            <p class="mb-1"><strong>SKU:</strong> {{ $product->sku }}</p>
                                            <p class="mb-1"><strong>Trạng thái:</strong>
                                                {{ $product->status ? 'Hoạt động' : 'Vô hiệu' }}</p>
                                            <p class="mb-1"><strong>Kho:</strong>
                                                {{ $product->total_stock > 0 ? 'Còn hàng' : 'Hết hàng' }}</p>
                                            <p class="mb-1"><strong>Danh mục:</strong>
                                                @foreach ($product->categories as $category)
                                                    <span class="badge bg-info me-1">{{ $category->name }}</span>
                                                @endforeach
                                            </p>
                                            <p class="mt-3">{{ Str::limit($product->description, 150, '...') }}</p>
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn btn-warning"><i class="fa-solid fa-pen me-2"></i>Sửa sản
                                                phẩm</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach --}}
{{-- 
                </div>
            </div>

            @if ($products->hasPages())
                <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="text-muted small">Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }}
                        trong {{ $products->total() }} sản phẩm</div>
                    <div>{{ $products->links('components.pagination') }}</div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle trạng thái
                document.querySelectorAll('.toggle-status').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        fetch(`/admin/products/${id}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        }).then(res => res.json()).then(res => {
                            if (res.success) {
                                this.classList.toggle('btn-success');
                                this.classList.toggle('btn-secondary');
                                this.textContent = this.classList.contains('btn-success') ?
                                    'Hoạt động' : 'Vô hiệu';
                            }
                        });
                    });
                });

                // Toggle kho
                document.querySelectorAll('.toggle-stock').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        fetch(`/admin/products/${id}/toggle-stock`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        }).then(res => res.json()).then(res => {
                            if (res.success) {
                                this.classList.toggle('btn-primary');
                                this.classList.toggle('btn-danger');
                                this.textContent = this.classList.contains('btn-primary') ?
                                    'Còn hàng' : 'Hết hàng';
                            }
                        });
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .table tbody tr {
                transition: all 0.25s ease;
                cursor: pointer;
            }

            .table tbody tr:hover {
                background-color: #f1f3f5;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            }

            .bg-gradient-primary {
                background: linear-gradient(135deg, #667eea, #764ba2);
            }

            .bg-gradient-success {
                background: linear-gradient(135deg, #11998e, #38ef7d);
            }

            .bg-gradient-warning {
                background: linear-gradient(135deg, #f093fb, #f5576c);
            }

            .bg-gradient-danger {
                background: linear-gradient(135deg, #fa709a, #fee140);
            }

            .badge {
                font-size: 0.8rem;
            }

            .toggle-status,
            .toggle-stock {
                min-width: 90px;
                font-weight: 600;
                transition: all 0.25s;
            }

            .toggle-status:hover,
            .toggle-stock:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .table-responsive {
                overflow-x: auto;
            }
        </style>
    @endpush
@endsection --}}


@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
    <div class="container-fluid px-4">

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h2 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-primary"></i> Quản lý sản phẩm
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Sản phẩm</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.products.create') }}"
                        class="btn btn-primary btn-lg shadow-sm d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Thêm sản phẩm
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            @php
                $stats = [
                    [
                        'title' => 'Tổng sản phẩm',
                        'value' => $products->total(),
                        'icon' => 'fa-boxes',
                        'bg' => 'primary',
                    ],
                    ['title' => 'Còn hàng', 'value' => $inStockCount, 'icon' => 'fa-check', 'bg' => 'success'],
                    [
                        'title' => 'Hết hàng',
                        'value' => $outOfStockCount,
                        'icon' => 'fa-triangle-exclamation',
                        'bg' => 'warning',
                    ],
                    [
                        'title' => 'Tổng biến thể',
                        'value' => $variantsCount,
                        'icon' => 'fa-layer-group',
                        'bg' => 'danger',
                    ],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="col-xl-3 col-md-6">
                    <div
                        class="card shadow-sm h-100 border-0 text-white position-relative overflow-hidden bg-gradient-{{ $stat['bg'] }}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">{{ $stat['title'] }}</h6>
                                <h2 class="fw-bold mb-0">{{ $stat['value'] }}</h2>
                            </div>
                            <div class="fs-1 opacity-25"><i class="fa-solid {{ $stat['icon'] }}"></i></div>
                        </div>
                        <div class="position-absolute top-0 end-0 opacity-10 display-1 pe-2 pt-2">
                            <i class="fa-solid {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-filter text-primary me-2"></i>Bộ lọc nâng cao</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control form-control-lg"
                            placeholder="Tên sản phẩm..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status"
                            class="form-select form-select-lg @if (request('status') != '') border-primary border-2 @endif">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Vô hiệu hóa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select name="category_id"
                            class="form-select form-select-lg @if (request('category_id')) border-primary border-2 @endif">
                            <option value="">Tất cả</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit"
                            class="btn btn-primary btn-lg flex-fill d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-filter"></i> Lọc
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                            class="btn btn-outline-secondary btn-lg flex-fill d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-rotate-right"></i> Đặt lại
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th>#</th>
                                <th class="text-start">Sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Trạng thái</th>
                                <th>Giá</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr class="border-bottom">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="d-flex align-items-center gap-3 text-start">
                                        @php $image = optional($product->images()->wherePivot('is_main', true)->first())->path; @endphp
                                        @if ($image)
                                            <img src="{{ asset('storage/' . $image) }}" class="rounded shadow-sm"
                                                style="width:50px;height:50px;object-fit:cover">
                                        @else
                                            <div class="rounded bg-gradient-primary text-white d-flex justify-content-center align-items-center shadow-sm fw-bold"
                                                style="width:50px;height:50px;font-size:20px;">
                                                {{ strtoupper(substr($product->name ?? 'P', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="text-start">
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <div class="text-muted small">{{ $product->sku }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach ($product->categories as $category)
                                            <span class="badge bg-info me-1">{{ $category->name }}</span>
                                        @endforeach
                                        {{-- {{ $product->categories->first()->name ?? '-' }} --}}
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-gradient-{{ $product->status ? 'success' : 'secondary' }}">
                                            {{ $product->status ? 'Hoạt động' : 'Vô hiệu' }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ number_format($product->price, 0, ',', '.') }}₫</td>
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="javascript:;" class="btn btn-outline-info btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $product->id }}"
                                                title="Xem nhanh">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn btn-outline-warning btn-sm" title="Sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-action="{{ route('admin.products.destroy', $product->id) }}"
                                                data-name="{{ $product->name }}" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-muted text-center">
                                        <i class="fa-solid fa-inbox fs-1 mb-3 opacity-25"></i>
                                        <div>Không có sản phẩm nào</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="text-muted small">Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }}
                        trong {{ $products->total() }} sản phẩm</div>
                    <div>{{ $products->links('components.pagination') }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Styles -->
    <style>
        body,
        .container-fluid,
        table,
        .card,
        .form-label,
        .btn {
            font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #fa709a, #fee140);
        }

        .table tbody tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-group .btn {
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
        }

        .badge {
            font-size: .8rem;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Tooltip
                [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].map(el => new bootstrap.Tooltip(el));

                // Xóa mềm
                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.dataset.action;
                        const name = this.dataset.name;

                        Swal.fire({
                            title: 'Xác nhận xóa',
                            html: `<p>Bạn có chắc chắn muốn xóa sản phẩm <strong>${name}</strong>?</p>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Xóa',
                            cancelButtonText: 'Hủy',
                            reverseButtons: true
                        }).then(result => {
                            if (result.isConfirmed) {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = url;
                                const csrf = document.createElement('input');
                                csrf.type = 'hidden';
                                csrf.name = '_token';
                                csrf.value = document.querySelector('meta[name="csrf-token"]')
                                    .content;
                                form.appendChild(csrf);
                                const method = document.createElement('input');
                                method.type = 'hidden';
                                method.name = '_method';
                                method.value = 'DELETE';
                                form.appendChild(method);
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
