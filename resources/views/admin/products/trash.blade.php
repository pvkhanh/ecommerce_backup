{{-- @extends('admin.layouts.app')

@section('title', 'Thùng rác sản phẩm')

@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="fas fa-trash-alt text-danger me-2"></i>Thùng rác sản phẩm</h1>
            <div class="d-flex gap-2">
                @if ($products->count() > 0)
                    <!-- Khôi phục tất cả -->
                    <form action="{{ route('admin.products.restoreAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-undo-alt me-2"></i>Khôi phục tất cả
                        </button>
                    </form>

                    <!-- Xóa tất cả vĩnh viễn -->
                    <form id="forceDeleteAllForm" action="{{ route('admin.products.forceDeleteAll') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" onclick="confirmForceDeleteAll()">
                            <i class="fas fa-trash me-2"></i>Xóa vĩnh viễn tất cả
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-recycle me-2"></i>Danh sách sản phẩm đã xóa</span>
            </div>
            <div class="card-body p-0">
                @if ($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Ngày xóa</th>
                                    <th width="200">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + $products->firstItem() }}</td>
                                        <td>
                                            @php
                                                $img = $product->primary_image_id
                                                    ? $product->images->where('id', $product->primary_image_id)->first()
                                                    : $product->images->first();
                                            @endphp
                                            @if ($img)
                                                <img src="{{ asset('storage/' . $img->path) }}" class="rounded shadow-sm"
                                                    style="width: 70px; height: 70px; object-fit: cover;">
                                            @else
                                                <span class="text-muted fst-italic">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ number_format($product->price, 0, ',', '.') }}₫</td>
                                        <td>{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Khôi phục -->
                                                <form id="restoreForm{{ $product->id }}"
                                                    action="{{ route('admin.products.restore', $product->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="confirmRestore({{ $product->id }})">
                                                        <i class="fas fa-undo-alt me-1"></i>Khôi phục
                                                    </button>
                                                </form>

                                                <!-- Xóa vĩnh viễn -->
                                                <form id="forceDeleteForm{{ $product->id }}"
                                                    action="{{ route('admin.products.forceDelete', $product->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmForceDelete({{ $product->id }})">
                                                        <i class="fas fa-trash me-1"></i>Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Không có sản phẩm nào trong thùng rác.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmRestore(id) {
            Swal.fire({
                title: "Khôi phục sản phẩm?",
                text: "Sản phẩm này sẽ được đưa trở lại danh sách chính.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#10b981",
                cancelButtonColor: "#64748b",
                confirmButtonText: '<i class="fas fa-undo-alt me-2"></i>Khôi phục',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restoreForm' + id).submit();
                }
            });
        }

        function confirmForceDelete(id) {
            Swal.fire({
                title: "Xóa vĩnh viễn?",
                text: "Hành động này không thể hoàn tác!",
                icon: "error",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Xóa vĩnh viễn',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('forceDeleteForm' + id).submit();
                }
            });
        }

        function confirmForceDeleteAll() {
            Swal.fire({
                title: "Xóa tất cả vĩnh viễn?",
                text: "Tất cả sản phẩm trong thùng rác sẽ bị xóa khỏi hệ thống!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#b91c1c",
                cancelButtonColor: "#64748b",
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Xóa tất cả',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('forceDeleteAllForm').submit();
                }
            });
        }
    </script>
@endpush --}}


@extends('admin.layouts.app')

@section('title', 'Thùng rác sản phẩm')

@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-trash-alt text-danger me-2"></i>Thùng rác sản phẩm
            </h1>

            <div class="d-flex gap-2">
                @if ($products->count() > 0)
                    <!-- Bulk Restore -->
                    <button type="button" class="btn btn-success" id="bulkRestoreBtn">
                        <i class="fas fa-undo-alt me-2"></i>Khôi phục đã chọn
                    </button>

                    <!-- Bulk Force Delete -->
                    <button type="button" class="btn btn-danger" id="bulkForceDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Xóa vĩnh viễn đã chọn
                    </button>

                    <!-- Restore All -->
                    <form id="restoreAllForm" action="{{ route('admin.products.restoreAll') }}" method="POST"
                        class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-success" onclick="confirmRestoreAll()">
                        <i class="fas fa-undo-alt me-2"></i>Khôi phục tất cả
                    </button>

                    <!-- Force Delete All -->
                    <form id="forceDeleteAllForm" action="{{ route('admin.products.forceDeleteAll') }}" method="POST"
                        class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn btn-danger" onclick="confirmForceDeleteAll()">
                        <i class="fas fa-trash me-2"></i>Xóa vĩnh viễn tất cả
                    </button>
                @endif

                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-recycle me-2"></i>Danh sách sản phẩm đã xóa</span>
                <div>
                    <input type="checkbox" id="selectAll">
                    <label for="selectAll" class="mb-0">Chọn tất cả</label>
                </div>
            </div>

            <div class="card-body p-0">
                @if ($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th width="50"></th>
                                    <th>#</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Ngày xóa</th>
                                    <th width="200">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @php
                                                $img = $product->primary_image_id
                                                    ? $product->images->where('id', $product->primary_image_id)->first()
                                                    : $product->images->first();
                                            @endphp
                                            @if ($img)
                                                <img src="{{ asset('storage/' . $img->path) }}" class="rounded shadow-sm"
                                                    style="width:70px;height:70px;object-fit:cover;">
                                            @else
                                                <span class="text-muted fst-italic">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ number_format($product->price, 0, ',', '.') }}₫</td>
                                        <td>{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Restore -->
                                                <form id="restoreForm{{ $product->id }}"
                                                    action="{{ route('admin.products.restore', $product->id) }}"
                                                    method="POST" class="d-none">@csrf</form>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    onclick="confirmRestore({{ $product->id }})">
                                                    <i class="fas fa-undo-alt me-1"></i>Khôi phục
                                                </button>

                                                <!-- Force Delete -->
                                                <form id="forceDeleteForm{{ $product->id }}"
                                                    action="{{ route('admin.products.forceDelete', $product->id) }}"
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmForceDelete({{ $product->id }})">
                                                    <i class="fas fa-trash me-1"></i>Xóa
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Không có sản phẩm nào trong thùng rác.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Select all checkbox
            $(document).on('change', '#selectAll', function() {
                $('.product-checkbox').prop('checked', this.checked);
            });

            // Bulk Restore
            $('#bulkRestoreBtn').click(function() {
                const ids = $('.product-checkbox:checked').map(function() {
                    return this.value;
                }).get();
                if (ids.length === 0) {
                    Swal.fire('Lỗi', 'Vui lòng chọn ít nhất 1 sản phẩm', 'warning');
                    return;
                }
                Swal.fire({
                    title: 'Khôi phục sản phẩm đã chọn?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Khôi phục',
                    cancelButtonText: 'Hủy',
                    reverseButtons: true
                }).then(result => {
                    if (result.isConfirmed) ids.forEach(id => $('#restoreForm' + id).submit());
                });
            });

            // Bulk Force Delete
            $('#bulkForceDeleteBtn').click(function() {
                const ids = $('.product-checkbox:checked').map(function() {
                    return this.value;
                }).get();
                if (ids.length === 0) {
                    Swal.fire('Lỗi', 'Vui lòng chọn ít nhất 1 sản phẩm', 'warning');
                    return;
                }
                Swal.fire({
                    title: 'Xóa vĩnh viễn sản phẩm đã chọn?',
                    text: 'Hành động này không thể hoàn tác!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa vĩnh viễn',
                    cancelButtonText: 'Hủy',
                    reverseButtons: true
                }).then(result => {
                    if (result.isConfirmed) ids.forEach(id => $('#forceDeleteForm' + id).submit());
                });
            });
        });

        // Restore All
        function confirmRestoreAll() {
            Swal.fire({
                title: 'Khôi phục tất cả?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Khôi phục tất cả',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) $('#restoreAllForm').submit();
            });
        }

        // Force Delete All
        function confirmForceDeleteAll() {
            Swal.fire({
                title: 'Xóa tất cả vĩnh viễn?',
                text: 'Hành động này không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa tất cả',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) $('#forceDeleteAllForm').submit();
            });
        }

        // Restore 1 sản phẩm
        function confirmRestore(id) {
            Swal.fire({
                title: 'Khôi phục sản phẩm?',
                text: 'Sản phẩm sẽ được đưa về danh sách chính',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Khôi phục',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) $('#restoreForm' + id).submit();
            });
        }

        // Force Delete 1 sản phẩm
        function confirmForceDelete(id) {
            Swal.fire({
                title: 'Xóa vĩnh viễn?',
                text: 'Hành động này không thể hoàn tác!',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) $('#forceDeleteForm' + id).submit();
            });
        }
    </script>
@endpush
