@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-eye text-info me-2"></i>Chi tiết sản phẩm
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                                <li class="breadcrumb-item active">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg shadow-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        @if ($product->images()->wherePivot('is_main', true)->first())
                            <img src="{{ asset('storage/' . $product->images()->wherePivot('is_main', true)->first()->path) }}"
                                class="img-fluid rounded shadow-sm" alt="{{ $product->name }}">
                        @else
                            <div class="rounded bg-gradient-primary text-white d-flex justify-content-center align-items-center fw-bold"
                                style="width:100%;height:250px;font-size:50px;">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ $product->name }}</h3>
                        <p class="text-muted mb-1"><strong>SKU:</strong> {{ $product->sku ?? '-' }}</p>
                        <p class="mb-1"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }}₫</p>
                        <p class="mb-1"><strong>Trạng thái:</strong>
                            <span class="badge bg-gradient-{{ $product->status ? 'success' : 'secondary' }}">
                                {{ $product->status ? 'Hoạt động' : 'Vô hiệu' }}
                            </span>
                        </p>
                        <p class="mb-1"><strong>Danh mục:</strong>
                            @foreach ($product->categories as $category)
                                <span class="badge bg-info me-1">{{ $category->name }}</span>
                            @endforeach
                        </p>
                        <p class="text-muted mb-0"><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
