{{-- @extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Danh sách danh mục</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Thêm danh mục</a>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa danh mục này?')" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Không có danh mục nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('components.pagination', ['data' => $categories])
@endsection --}}


{{--

@extends('layouts.admin')

@section('title', 'Thêm danh mục')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Thêm danh mục mới</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @include('admin.categories._form')
            </form>
        </div>
    </div>
@endsection --}}


{{-- @extends('layouts.admin')

@section('title', 'Thêm danh mục')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Thêm danh mục mới</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @include('admin.categories._form')
            </form>
        </div>
    </div>
@endsection --}}


@extends('layouts.admin')

@section('title', 'Thêm danh mục')

@section('content')
    <div class="page-heading mb-4">
        <h3 class="fw-bold text-primary">
            <i class="fa-solid fa-folder-plus me-2"></i> Thêm danh mục
        </h3>
    </div>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @include('admin.categories._form')
            </form>
        </div>
    </div>
@endsection
