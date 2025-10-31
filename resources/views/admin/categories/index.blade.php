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

@section('title', 'Danh sách danh mục')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Danh mục</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">+ Thêm danh mục</a>
    </div>

    <form method="GET" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..." value="{{ $keyword }}">
    </form>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Slug</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{!! $category->is_active
                        ? '<span class="badge bg-success">Hoạt động</span>'
                        : '<span class="badge bg-secondary">Ẩn</span>' !!}</td>
                    <td>{{ $category->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Không có danh mục nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $categories->links('components.pagination') }}
@endsection --}}


{{--
@extends('layouts.admin')

@section('title', 'Danh sách danh mục')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Danh sách danh mục</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">+ Thêm danh mục</a>
    </div>

    <form method="GET" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..."
            value="{{ $keyword ?? '' }}">
    </form>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Slug</th>
                <th>Kích hoạt</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        @if ($category->is_active)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>{{ optional($category->created_at)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                @include('admin.shared.empty', ['colspan' => 6])
            @endforelse
        </tbody>
    </table>

    {{ $categories->links('components.pagination') }}
@endsection --}}


@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-warning mb-1"><i class="fa-solid fa-tags me-2"></i>Danh mục sản phẩm</h3>
            <p class="text-muted mb-0">Quản lý các nhóm sản phẩm</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-warning text-dark shadow-sm px-3">
            <i class="fa-solid fa-plus me-1"></i> Thêm danh mục
        </a>
    </div>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control border-warning" placeholder="🔍 Tìm kiếm danh mục..."
                value="{{ request('search') }}">
            <button class="btn btn-outline-warning text-dark" type="submit">Tìm</button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Ngày tạo</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td class="text-muted">{{ $category->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <x-action-buttons :show="route('admin.categories.show', $category->id)" :edit="route('admin.categories.edit', $category->id)" :delete="route('admin.categories.destroy', $category->id)" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fa-solid fa-folder-open fs-4 mb-2"></i><br>Không có danh mục nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $categories->links('components.pagination') }}
    </div>
@endsection
