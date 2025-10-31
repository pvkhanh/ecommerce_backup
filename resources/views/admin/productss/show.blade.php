@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="h3 mb-3">{{ $product->name }}</h1>

        <div class="row">
            <div class="col-md-4">
                @if ($product->mainImage)
                    <img src="{{ asset('storage/' . $product->mainImage->path) }}" alt="{{ $product->name }}"
                        class="img-fluid mb-3">
                @endif

                @if ($product->galleryImages)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($product->galleryImages as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" class="img-thumbnail" style="width:80px;">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr>
                        <th>SKU</th>
                        <td>{{ $product->sku }}</td>
                    </tr>
                    <tr>
                        <th>Giá</th>
                        <td>{{ number_format($product->price, 0, '.', ',') }} ₫</td>
                    </tr>
                    <tr>
                        <th>Danh mục</th>
                        <td>{{ $product->category?->name }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @if ($product->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Mô tả</th>
                        <td>{!! nl2br(e($product->description)) !!}</td>
                    </tr>
                    <tr>
                        <th>Tồn kho</th>
                        <td>
                            @if ($product->stocks && $product->stocks->count())
                                @foreach ($product->stocks as $stock)
                                    <div>{{ $stock->location }}: {{ $stock->quantity }}</div>
                                @endforeach
                            @else
                                <div>Chưa có tồn kho</div>
                            @endif

                        </td>
                    </tr>
                </table>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">Sửa</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
@endsection
