@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

@section('title', 'Quản lý ảnh')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Quản lý ảnh</h1>
            <a href="{{ route('admin.images.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tải lên ảnh mới
            </a>
        </div>

        <!-- Bộ lọc -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.images.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Loại ảnh</label>
                        <select name="type" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
                            <option value="avatar" {{ request('type') == 'avatar' ? 'selected' : '' }}>Avatar</option>
                            <option value="banner" {{ request('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                            <option value="blog" {{ request('type') == 'blog' ? 'selected' : '' }}>Blog</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên file, mô tả..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- <!-- Thông báo -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif --}}

        <!-- Lưới ảnh -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    @forelse ($images as $image)
                        @php
                            $path = trim($image->path ?? '');
                            // 🔹 Chuẩn hóa URL ảnh
                            if (empty($path)) {
                                $imageUrl = asset('images/no-image.png');
                            } elseif (Str::startsWith($path, ['http://', 'https://'])) {
                                $imageUrl = $path;
                            } else {
                                $imageUrl = asset('storage/' . ltrim($path, '/'));
                            }
                        @endphp

                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <div class="card h-100 shadow-sm border-0 hover-shadow position-relative">
                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $image->alt_text ?? 'Image' }}"
                                    onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';"
                                    style="height: 150px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                                <span
                                    class="position-absolute top-0 end-0 badge bg-{{ $image->is_active ? 'success' : 'secondary' }} m-1">
                                    {{ $image->is_active ? 'Active' : 'Inactive' }}
                                </span>

                                <div class="card-body p-2">
                                    <small class="d-block text-truncate mb-1">
                                        <strong>Type:</strong> {{ ucfirst($image->type ?? 'Unknown') }}
                                    </small>
                                    <small class="d-block text-truncate mb-2" title="{{ $image->alt_text }}">
                                        {{ $image->alt_text ?: 'Không có mô tả' }}
                                    </small>

                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.images.edit', $image) }}"
                                            class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.images.destroy', $image) }}" method="POST"
                                            class="flex-fill"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa ảnh này không?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> Không có ảnh nào.
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Phân trang -->
                <div class="mt-4">
                    {{ $images->links('components.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
