@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <!-- Products -->
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-2">Sản phẩm</h6>
                    <h3 class="fw-bold mb-0">{{ $productsCount ?? 0 }}</h3>
                </div>
                <div class="avatar avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-package-variant fs-3 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-2">Danh mục</h6>
                    <h3 class="fw-bold mb-0">{{ $categoriesCount ?? 0 }}</h3>
                </div>
                <div class="avatar avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-shape fs-3 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-2">Đơn hàng</h6>
                    <h3 class="fw-bold mb-0">{{ $ordersCount ?? 0 }}</h3>
                </div>
                <div class="avatar avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-cart-outline fs-3 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-2">Người dùng</h6>
                    <h3 class="fw-bold mb-0">{{ $usersCount ?? 0 }}</h3>
                </div>
                <div class="avatar avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-account-outline fs-3 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
