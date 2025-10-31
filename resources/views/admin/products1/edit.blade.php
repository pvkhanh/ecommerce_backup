{{-- @extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
    <h4 class="mb-3">Sửa sản phẩm</h4>
    <form method="POST" action="{{ route('admin.products.update', $product) }}">
        @csrf @method('PUT')
        @include('admin.products._form')
        <button type="submit" class="btn btn-success mt-2">Cập nhật</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-2">Hủy</a>
    </form>
@endsection --}}

{{-- @extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Cập nhật sản phẩm</h5>
            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                @method('PUT')
                @include('admin.products._form')
            </form>
        </div>
    </div>
@endsection --}}



@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
    <div class="container-fluid px-4">

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-2">
                        <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Thêm sản phẩm
                    </h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                            <li class="breadcrumb-item active">Thêm sản phẩm</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fa-solid fa-rotate-left me-2"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.products._form')
                </form>

            </div>
        </div>
    </div>
@endsection
