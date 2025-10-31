@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Danh sách sản phẩm</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm sản phẩm</a>
        </div>

        <form action="{{ route('admin.products.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary">Tìm</button>
            </div>
        </form>

        @if ($products->count())
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>SKU</th>
                            <th>Giá</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $p)
                            <tr>
                                <td>{{ $products->firstItem() + $index }}</td>
                                <td>
                                    @if ($p->mainImage)
                                        <img src="{{ asset('storage/' . $p->mainImage->path) }}" alt="{{ $p->name }}"
                                            class="img-thumbnail" style="width:60px;height:60px;">
                                    @endif
                                </td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->sku }}</td>
                                <td>{{ number_format($p->price, 0, '.', ',') }} ₫</td>
                                <td>{{ $p->category?->name }}</td>
                                <td>
                                    @if ($p->is_active)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Ẩn</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $p->id) }}"
                                        class="btn btn-sm btn-info">Xem</a>
                                    <a href="{{ route('admin.products.edit', $p->id) }}"
                                        class="btn btn-sm btn-warning">Sửa</a>
                                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $products->withQueryString()->links() }}
        @else
            <div class="alert alert-info">Chưa có sản phẩm nào.</div>
        @endif
    </div>
@endsection
