@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
    <div class="container-fluid py-4">

        {{-- Breadcrumb & Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0"><i class="fas fa-edit me-2 text-primary"></i>Chỉnh sửa sản phẩm</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small text-muted mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info me-2">
                    <i class="fas fa-eye me-2"></i>Xem chi tiết
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" id="productForm">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Left Column --}}
                <div class="col-lg-8">
                    {{-- Thông tin cơ bản --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tên sản phẩm <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="productName"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name) }}" placeholder="Nhập tên sản phẩm" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Slug (URL thân thiện)</label>
                                <input type="text" name="slug" id="productSlug"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    value="{{ old('slug', $product->slug) }}" placeholder="tu-dong-tao-neu-de-trong"
                                    data-product-id="{{ $product->id }}">
                                <small class="text-muted">Để trống để tự động tạo từ tên sản phẩm</small>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label fw-semibold">SKU (Mã sản phẩm)</label>
                                <input type="text" name="sku"
                                    class="form-control @error('sku') is-invalid @enderror"
                                    value="{{ old('sku', $product->sku) }}"
                                    placeholder="VD: SP-001">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mô tả sản phẩm</label>
                                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Hình ảnh --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Hình ảnh sản phẩm</h5>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#imageModal">
                                <i class="fas fa-plus me-1"></i>Chọn ảnh
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="selectedImagesContainer" class="row g-3">
                                <div class="col-12 text-center text-muted py-5">
                                    <div class="spinner-border" role="status"></div>
                                    <p class="mt-2">Đang tải ảnh...</p>
                                </div>
                            </div>
                            <input type="hidden" name="primary_image_id" id="primaryImageId"
                                value="{{ $primaryImage?->id }}">
                            <input type="hidden" id="existingImageIds" value="{{ implode(',', $selectedImageIds) }}">
                            <input type="hidden" id="existingPrimaryImageId" value="{{ $primaryImage?->id }}">
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-lg-4">
                    {{-- Giá & Trạng thái --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Giá & Trạng thái</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Giá sản phẩm <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price', $product->price) }}" placeholder="0" min="0"
                                        step="any" required>
                                    <span class="input-group-text">đ</span>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label fw-semibold">Giá khuyến mãi</label>
                                <div class="input-group">
                                    <input type="number" name="sale_price"
                                        class="form-control @error('sale_price') is-invalid @enderror"
                                        value="{{ old('sale_price', $product->sale_price) }}"
                                        placeholder="0" min="0" step="1000">
                                    <span class="input-group-text">đ</span>
                                </div>
                                @error('sale_price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Trạng thái <span
                                        class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}"
                                            {{ old('status', $product->status->value) == $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Danh mục --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Danh mục</h5>
                        </div>
                        <div class="card-body">
                            <div class="category-checkboxes" style="max-height: 300px; overflow-y: auto;">
                                @php
                                    $selectedCategoryIds = old(
                                        'category_ids',
                                        $product->categories->pluck('id')->toArray(),
                                    );

                                    function renderCategoriesAccordion($categories, $selectedCategoryIds, $level = 0)
                                    {
                                        foreach ($categories as $category) {
                                            $checked = in_array($category->id, $selectedCategoryIds) ? 'checked' : '';
                                            $hasChildren = $category->children->count() > 0;
                                            $margin = $level * 20;

                                            echo '<div class="form-check mb-2" style="margin-left: ' . $margin . 'px">';
                                            echo '<input class="form-check-input category-toggle" type="checkbox" name="category_ids[]" id="cat' .
                                                $category->id .
                                                '" value="' .
                                                $category->id .
                                                '" ' .
                                                $checked .
                                                ($hasChildren ? ' data-has-children="1"' : '') .
                                                '>';
                                            echo '<label class="form-check-label fw-semibold" for="cat' .
                                                $category->id .
                                                '">' .
                                                $category->name .
                                                '</label>';
                                            echo '</div>';

                                            if ($hasChildren) {
                                                echo '<div class="children" style="display:none; margin-left: ' .
                                                    ($margin + 20) .
                                                    'px;">';
                                                renderCategoriesAccordion(
                                                    $category->children,
                                                    $selectedCategoryIds,
                                                    $level + 1,
                                                );
                                                echo '</div>';
                                            }
                                        }
                                    }

                                    renderCategoriesAccordion($categories, $selectedCategoryIds);
                                @endphp
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info w-100 mb-2">
                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    {{-- Image Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn hình ảnh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="imageSearch" placeholder="Tìm kiếm ảnh...">
                    </div>
                    <div class="row g-3" id="imageGallery">
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Đang tải...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="confirmImageSelection">
                        <i class="fas fa-check me-2"></i>Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Initialize with existing images
            let selectedImages = @json($selectedImageIds);
            let primaryImageId = {{ $primaryImage?->id ?? 'null' }};
            let allImages = [];

            // Auto-generate slug
            let isManualSlug = false;
            document.getElementById('productSlug').addEventListener('input', function() {
                isManualSlug = this.value.length > 0;
            });

            document.getElementById('productName').addEventListener('input', function(e) {
                if (isManualSlug) return;

                const slug = e.target.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd')
                    .replace(/Đ/g, 'd')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-+|-+$/g, '');
                document.getElementById('productSlug').value = slug;
            });

            // Load images when modal opens
            document.getElementById('imageModal').addEventListener('shown.bs.modal', function() {
                loadImages();
            });

            function loadImages(search = '') {
                fetch(`{{ route('admin.images.api.list') }}?type=product&search=${search}&per_page=50`)
                    .then(res => res.json())
                    .then(data => {
                        allImages = data.data || data.images || [];
                        renderImageGallery(allImages);
                    })
                    .catch(error => {
                        console.error('Error loading images:', error);
                        document.getElementById('imageGallery').innerHTML =
                            '<div class="col-12 text-center text-danger py-5">Không thể tải danh sách ảnh</div>';
                    });
            }

            function renderImageGallery(images) {
                const gallery = document.getElementById('imageGallery');

                if (!images || images.length === 0) {
                    gallery.innerHTML =
                        '<div class="col-12 text-center text-muted py-5"><i class="fas fa-images fa-3x mb-3 d-block"></i><p>Chưa có ảnh nào</p></div>';
                    return;
                }

                gallery.innerHTML = '';

                images.forEach(image => {
                    const isSelected = selectedImages.includes(image.id);
                    const col = document.createElement('div');
                    col.className = 'col-md-2 col-sm-3 col-4';
                    col.innerHTML = `
                        <div class="card image-select-card ${isSelected ? 'border-primary selected' : ''}"
                             style="cursor: pointer;"
                             data-image-id="${image.id}"
                             onclick="toggleImageSelection(${image.id})">
                            <img src="/storage/${image.path}" class="card-img-top"
                                 style="height: 120px; object-fit: cover;"
                                 alt="${image.alt_text || 'Product image'}">
                            ${isSelected ? '<div class="position-absolute top-0 end-0 m-1"><i class="fas fa-check-circle text-primary fa-2x"></i></div>' : ''}
                        </div>
                    `;
                    gallery.appendChild(col);
                });
            }

            function toggleImageSelection(imageId) {
                const index = selectedImages.indexOf(imageId);

                if (index > -1) {
                    selectedImages.splice(index, 1);
                    if (primaryImageId === imageId) {
                        primaryImageId = selectedImages[0] || null;
                    }
                } else {
                    selectedImages.push(imageId);
                    if (!primaryImageId) {
                        primaryImageId = imageId;
                    }
                }

                renderImageGallery(allImages);
            }

            document.getElementById('confirmImageSelection').addEventListener('click', function() {
                updateSelectedImagesDisplay();
                bootstrap.Modal.getInstance(document.getElementById('imageModal')).hide();
            });

            function updateSelectedImagesDisplay() {
                const container = document.getElementById('selectedImagesContainer');
                container.innerHTML = '';

                if (selectedImages.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center text-muted py-5">
                            <i class="fas fa-image fa-3x mb-3 d-block opacity-25"></i>
                            <p>Chưa có ảnh nào được chọn</p>
                        </div>
                    `;
                    return;
                }

                selectedImages.forEach(imageId => {
                    const image = allImages.find(img => img.id === imageId);
                    if (image) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                            <div class="card ${primaryImageId === imageId ? 'border-primary border-3' : ''}">
                                <img src="/storage/${image.path}" class="card-img-top"
                                     style="height: 150px; object-fit: cover;"
                                     alt="${image.alt_text || 'Product image'}">
                                <div class="card-body p-2">
                                    <input type="hidden" name="image_ids[]" value="${imageId}">
                                    ${primaryImageId === imageId ? '<span class="badge bg-primary w-100 mb-1"><i class="fas fa-star me-1"></i>Ảnh chính</span>' : ''}
                                    <div class="btn-group btn-group-sm w-100">
                                        <button type="button" class="btn btn-outline-primary"
                                                onclick="setPrimaryImage(${imageId})"
                                                data-bs-toggle="tooltip" title="Đặt làm ảnh chính">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger"
                                                onclick="removeImage(${imageId})"
                                                data-bs-toggle="tooltip" title="Xóa ảnh">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.appendChild(col);
                    }
                });

                document.getElementById('primaryImageId').value = primaryImageId || '';

                // Reinitialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            function setPrimaryImage(imageId) {
                primaryImageId = imageId;
                updateSelectedImagesDisplay();
            }

            function removeImage(imageId) {
                selectedImages = selectedImages.filter(id => id !== imageId);
                if (primaryImageId === imageId) {
                    primaryImageId = selectedImages[0] || null;
                }
                updateSelectedImagesDisplay();
            }

            // Search images
            let searchTimeout;
            const searchInput = document.getElementById('imageSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        loadImages(e.target.value);
                    }, 500);
                });
            }

            // Load existing images on page load
            window.addEventListener('DOMContentLoaded', function() {
                fetch(`{{ route('admin.images.api.list') }}?type=product&per_page=50`)
                    .then(res => res.json())
                    .then(data => {
                        allImages = data.data || data.images || [];
                        updateSelectedImagesDisplay();
                    })
                    .catch(error => {
                        console.error('Error loading images:', error);
                    });
            });

            // Form validation
            document.getElementById('productForm').addEventListener('submit', function(e) {
                const name = document.getElementById('productName').value.trim();
                const price = document.querySelector('input[name="price"]').value;

                if (!name) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập tên sản phẩm',
                        confirmButtonColor: '#4f46e5'
                    });
                    return false;
                }

                if (!price || parseFloat(price) < 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng nhập giá hợp lệ',
                        confirmButtonColor: '#4f46e5'
                    });
                    return false;
                }

                // Show loading
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng đợi',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                return true;
            });

            // Success message
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggles = document.querySelectorAll('.category-toggle[data-has-children="1"]');

                toggles.forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        const parentDiv = this.closest('.form-check');
                        const childrenDiv = parentDiv.nextElementSibling;
                        if (childrenDiv && childrenDiv.classList.contains('children')) {
                            childrenDiv.style.display = this.checked ? 'block' : 'none';
                        }
                    });

                    // Nếu checkbox đã được check từ đầu, show children
                    if (toggle.checked) {
                        const parentDiv = toggle.closest('.form-check');
                        const childrenDiv = parentDiv.nextElementSibling;
                        if (childrenDiv && childrenDiv.classList.contains('children')) {
                            childrenDiv.style.display = 'block';
                        }
                    }
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            }

            .card-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-bottom: 2px solid #e2e8f0;
                border-radius: 12px 12px 0 0 !important;
            }

            .form-control,
            .form-select {
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                transition: all 0.2s ease;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }

            .btn {
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.2s ease;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .btn-primary {
                background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%);
                border: none;
            }

            .btn-info {
                background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
                border: none;
                color: white;
            }

            .image-select-card {
                cursor: pointer;
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
                position: relative;
            }

            .image-select-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            .image-select-card.selected {
                border: 3px solid #4f46e5;
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
            }

            .image-select-card img {
                transition: all 0.3s ease;
            }

            .image-select-card:hover img {
                transform: scale(1.05);
            }

            .modal-content {
                border-radius: 16px;
                border: none;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            }

            .modal-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-bottom: 2px solid #e2e8f0;
                border-radius: 16px 16px 0 0;
            }

            .category-checkboxes::-webkit-scrollbar {
                width: 8px;
            }

            .category-checkboxes::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 10px;
            }

            .category-checkboxes::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%);
                border-radius: 10px;
            }
        </style>
    @endpush
@endsection
