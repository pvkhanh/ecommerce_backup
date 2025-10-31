{{-- @extends('admin.layouts.app')

@section('title', 'Tải lên ảnh mới')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h1 class="h3 mb-0">Tải lên ảnh mới</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.images.index') }}">Quản lý ảnh</a></li>
                    <li class="breadcrumb-item active">Tải lên</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Chọn ảnh <span class="text-danger">*</span></label>
                                <input type="file" name="images[]"
                                    class="form-control @error('images.*') is-invalid @enderror" multiple accept="image/*"
                                    id="imageInput" required>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Chấp nhận: JPG, PNG, GIF, WEBP. Tối đa 2MB mỗi ảnh. Có thể chọn nhiều ảnh.
                                </small>
                            </div>

                            <!-- Preview Container -->
                            <div id="imagePreviewContainer" class="mb-3 row g-2"></div>

                            <div class="mb-3">
                                <label class="form-label">Loại ảnh <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">-- Chọn loại --</option>
                                    <option value="product">Sản phẩm</option>
                                    <option value="avatar">Avatar</option>
                                    <option value="banner">Banner</option>
                                    <option value="blog">Blog</option>
                                    <option value="category">Danh mục</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Tải lên
                                </button>
                                <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Hướng dẫn</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                Chọn nhiều ảnh cùng lúc
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                Kích thước tối đa 2MB/ảnh
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                Format: JPG, PNG, GIF, WEBP
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-info-circle text-info"></i>
                                Có thể thêm mô tả cho từng ảnh
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('imageInput').addEventListener('change', function(e) {
                const container = document.getElementById('imagePreviewContainer');
                container.innerHTML = '';

                Array.from(e.target.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                <div class="card">
                    <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;">
                    <div class="card-body p-2">
                        <input type="text"
                               name="alt_text[]"
                               class="form-control form-control-sm"
                               placeholder="Mô tả ảnh ${index + 1}">
                    </div>
                </div>
            `;
                        container.appendChild(col);
                    }
                    reader.readAsDataURL(file);
                });
            });
        </script>
    @endpush
@endsection --}}



@extends('layouts.admin')

@section('title', 'Tải lên ảnh mới')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h1 class="h3 mb-0">Tải lên ảnh mới</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.images.index') }}">Quản lý ảnh</a></li>
                    <li class="breadcrumb-item active">Tải lên</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <!-- Form Upload -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <form id="uploadForm" action="{{ route('admin.images.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Drop Area -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kéo thả ảnh hoặc click chọn <span
                                        class="text-danger">*</span></label>
                                <div id="dropArea" class="border border-dashed rounded p-4 text-center bg-light"
                                    style="cursor:pointer; min-height:180px; transition: background 0.3s;">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                    <div class="text-muted">Kéo thả ảnh vào đây hoặc click để chọn</div>
                                    <div class="text-muted small">Tối đa 10 ảnh, mỗi ảnh ≤ 2MB, JPG/PNG/GIF/WEBP</div>
                                </div>
                                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" hidden>
                                @error('images.*')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nút xóa nhiều ảnh -->
                            <div class="mb-2 d-flex justify-content-end">
                                <button type="button" id="deleteSelected" class="btn btn-sm btn-danger"
                                    style="display:none;">
                                    <i class="fas fa-trash-alt"></i> Xóa ảnh đã chọn
                                </button>
                            </div>

                            <!-- Preview Container -->
                            <div id="imagePreviewContainer" class="mb-3 row g-3"></div>

                            <!-- Loại ảnh -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Loại ảnh <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">-- Chọn loại --</option>
                                    <option value="product">Sản phẩm</option>
                                    <option value="avatar">Avatar</option>
                                    <option value="banner">Banner</option>
                                    <option value="blog">Blog</option>
                                    <option value="category">Danh mục</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Tải lên
                                </button>
                                <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            <!-- Hướng dẫn -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Hướng dẫn</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Chọn nhiều ảnh cùng lúc</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Kích thước tối đa 2MB/ảnh
                            </li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Format: JPG, PNG, GIF, WEBP
                            </li>
                            <li class="mb-2"><i class="fas fa-info-circle text-info"></i> Có thể thêm mô tả cho từng ảnh
                            </li>
                            <li class="mb-2"><i class="fas fa-info-circle text-info"></i> Kéo thả để thay đổi thứ tự ảnh
                            </li>
                            <li class="mb-2"><i class="fas fa-exclamation-circle text-warning"></i> Giới hạn tối đa 10 ảnh
                            </li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Tick chọn để xóa nhiều ảnh
                                cùng lúc</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.16.0/Sortable.min.js"></script>
        <script>
            const dropArea = document.getElementById('dropArea');
            const fileInput = document.getElementById('imageInput');
            const container = document.getElementById('imagePreviewContainer');
            const deleteBtn = document.getElementById('deleteSelected');
            let imagesArray = [];
            const MAX_IMAGES = 10;

            // Click dropArea để mở file picker
            dropArea.addEventListener('click', () => fileInput.click());

            // Chọn file từ input
            fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

            // Drag & Drop
            dropArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropArea.classList.add('bg-light');
            });
            dropArea.addEventListener('dragleave', () => dropArea.classList.remove('bg-light'));
            dropArea.addEventListener('drop', (e) => {
                e.preventDefault();
                dropArea.classList.remove('bg-light');
                handleFiles(e.dataTransfer.files);
            });

            // Hàm xử lý file
            function handleFiles(files) {
                if (imagesArray.length + files.length > MAX_IMAGES) {
                    alert(`Bạn chỉ được tải lên tối đa ${MAX_IMAGES} ảnh`);
                    return;
                }
                Array.from(files).forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    if (file.size > 2 * 1024 * 1024) {
                        alert(`${file.name} quá lớn! (max 2MB)`);
                        return;
                    }
                    imagesArray.push(file);
                });
                renderImages();
            }

            // Render preview
            function renderImages() {
                container.innerHTML = '';
                let anyChecked = false;
                imagesArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.dataset.index = index;
                        col.innerHTML = `
                <div class="card shadow-sm border-0 hover-shadow position-relative">
                    <input type="checkbox" class="select-image position-absolute" style="top:5px; left:5px; z-index:10;">
                    <img src="${e.target.result}" class="card-img-top" style="height:130px; object-fit: cover;">
                    <div class="card-body p-2">
                        <input type="text" name="alt_text[]" class="form-control form-control-sm mb-1" placeholder="Mô tả ảnh ${index+1}">
                        <button type="button" class="btn btn-sm btn-danger w-100">Xóa</button>
                    </div>
                </div>
            `;
                        // Xóa từng ảnh
                        col.querySelector('button').addEventListener('click', () => {
                            imagesArray.splice(index, 1);
                            renderImages();
                        });
                        // Checkbox
                        col.querySelector('.select-image').addEventListener('change', () => {
                            anyChecked = container.querySelectorAll('.select-image:checked').length > 0;
                            deleteBtn.style.display = anyChecked ? 'inline-block' : 'none';
                        });
                        container.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Xóa nhiều ảnh cùng lúc
            deleteBtn.addEventListener('click', () => {
                const checkedBoxes = container.querySelectorAll('.select-image:checked');
                const indexesToDelete = Array.from(checkedBoxes).map(cb => parseInt(cb.closest('[data-index]').dataset
                    .index));
                imagesArray = imagesArray.filter((_, i) => !indexesToDelete.includes(i));
                renderImages();
            });

            // Reorder ảnh
            new Sortable(container, {
                animation: 150,
                onEnd: function(evt) {
                    const movedItem = imagesArray.splice(evt.oldIndex, 1)[0];
                    imagesArray.splice(evt.newIndex, 0, movedItem);
                    renderImages();
                }
            });

            // Submit form
            document.getElementById('uploadForm').addEventListener('submit', function(e) {
                // Tạo DataTransfer để đảm bảo tất cả ảnh trong imagesArray được gửi
                const dataTransfer = new DataTransfer();
                imagesArray.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            });
        </script>
    @endpush

@endsection
