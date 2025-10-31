{{-- @extends('layouts.admin')

@section('title', 'Sửa danh mục')

@section('content')
    <h4 class="mb-3">Sửa danh mục</h4>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf @method('PUT')
        @include('admin.categories._form')
        <button type="submit" class="btn btn-success mt-2">Cập nhật</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mt-2">Hủy</a>
    </form>
@endsection --}}


{{--
@extends('layouts.admin')

@section('title', 'Sửa danh mục')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Cập nhật danh mục</h5>
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @method('PUT')
                @include('admin.categories._form')
            </form>
        </div>
    </div>
@endsection --}}


{{--
@extends('layouts.admin')

@section('title', 'Sửa danh mục')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Cập nhật danh mục</h5>
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @method('PUT')
                @include('admin.categories._form')
            </form>
        </div>
    </div>
@endsection --}}


@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục')

@section('content')
    <div class="page-heading mb-4">
        <h3 class="fw-bold text-primary">
            <i class="fa-solid fa-pen-to-square me-2"></i> Chỉnh sửa danh mục
        </h3>
    </div>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @method('PUT')
                @include('admin.categories._form')
            </form>
        </div>
    </div>
@endsection
