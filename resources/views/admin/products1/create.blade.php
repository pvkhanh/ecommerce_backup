{{-- @extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
    <h4 class="mb-3">Thêm sản phẩm mới</h4>
    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        @include('admin.products._form')
        <button type="submit" class="btn btn-success mt-2">Lưu</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-2">Hủy</a>
    </form>
@endsection --}}


{{-- @extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Thêm sản phẩm mới</h5>
            <form action="{{ route('admin.products.store') }}" method="POST">
                @include('admin.products._form')
            </form>
        </div>
    </div>
@endsection --}}

{{-- @extends('layouts.admin')

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
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.products._form')
                </form>
            </div>
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
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Tên sản phẩm -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                            placeholder="Nhập tên sản phẩm">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug"
                            class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}"
                            placeholder="tên-sản-phẩm">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mã sản phẩm -->
                    {{-- <div class="mb-3">
                        <label for="sku" class="form-label">Mã sản phẩm / Mã vạch</label>
                        <input type="text" name="sku" id="sku"
                            class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}"
                            placeholder="Mã sản phẩm">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}
                    <div class="mb-3">
                        <label for="sku" class="form-label">Mã sản phẩm</label>

                        <select id="sku-select" class="form-select mb-2">
                            <option value="">-- Chọn mã tự sinh --</option>
                            @foreach ($skus as $sku)
                                <option value="{{ $sku }}">{{ $sku }}</option>
                            @endforeach
                        </select>

                        {{-- <input type="text" name="sku" id="sku" class="form-control"
                            placeholder="Hoặc nhập mã riêng" value="{{ old('sku') }}"> --}}
                    </div>

                    <!-- Giá bán và giá nhập -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price"
                                class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                                placeholder="Giá bán">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cost" class="form-label">Giá nhập</label>
                            <input type="number" name="cost" id="cost"
                                class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost') }}"
                                placeholder="Giá nhập">
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Số lượng -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Số lượng</label>
                        <input type="number" name="quantity" id="quantity"
                            class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 0) }}"
                            min="0">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Danh mục -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id"
                            class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Ảnh sản phẩm</label>
                        <input type="file" name="image" id="image"
                            class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="preview-image" src="#" class="img-fluid mt-2"
                            style="display:none; max-width:200px;">
                    </div>

                    <!-- Trạng thái -->
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description" id="description" rows="5" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success mt-2">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Lưu
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-2">
                        <i class="fa-solid fa-rotate-left me-2"></i>Hủy
                    </a>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Preview image before upload
            document.getElementById('image').addEventListener('change', function() {
                const [file] = this.files;
                if (file) {
                    const preview = document.getElementById('preview-image');
                    preview.src = URL.createObjectURL(file);
                    preview.style.display = 'block';
                }
            });

            // Optional: auto-generate slug
            document.getElementById('name').addEventListener('keyup', function() {
                const slug = this.value.toLowerCase()
                    .replace(/ /g, '-')
                    .replace(/[^\w-]+/g, '');
                document.getElementById('slug').value = slug;
            });
        </script>
        <script>
            document.getElementById('sku-select').addEventListener('change', function() {
                var skuInput = document.getElementById('sku');
                skuInput.value = this.value;
            });
        </script>
    @endpush
@endsection
