@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row text-center">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Sản phẩm</h5>
                    <h2>{{ $productsCount ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Danh mục</h5>
                    <h2>{{ $categoriesCount ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Đơn hàng</h5>
                    <h2>{{ $ordersCount ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Người dùng</h5>
                    <h2>{{ $usersCount ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
