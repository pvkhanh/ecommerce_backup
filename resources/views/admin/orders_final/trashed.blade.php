@extends('layouts.admin')

@section('title', 'Thùng rác - Đơn hàng')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                        </a>
                        <div>
                            <h2 class="fw-bold text-dark mb-1">
                                <i class="fa-solid fa-trash-can text-warning me-2"></i>
                                Thùng rác - Đơn hàng
                            </h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
                                    </li>
                                    <li class="breadcrumb-item active">Thùng rác</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    @if ($orders->total() > 0)
                        <button type="button" class="btn btn-danger btn-lg" id="emptyTrashBtn">
                            <i class="fa-solid fa-trash-can me-2"></i> Xóa tất cả
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alert Info -->
        @if ($orders->total() > 0)
            <div class="alert alert-warning d-flex align-items-center mb-4">
                <i class="fa-solid fa-info-circle fs-4 me-3"></i>
                <div>
                    <h6 class="mb-1 fw-bold">Đơn hàng trong thùng rác</h6>
                    <p class="mb-0 small">Các đơn hàng này đã bị xóa tạm thời. Bạn có thể khôi phục hoặc xóa vĩnh viễn
                        chúng.</p>
                </div>
            </div>
        @endif

        <!-- Trashed Orders Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-list text-warning me-2"></i>Đơn hàng đã xóa
                    <span class="badge bg-warning text-dark fs-6">{{ $orders->total() }} đơn</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-center" style="width:80px;">#</th>
                                <th class="px-4 py-3">Mã đơn hàng</th>
                                <th class="px-4 py-3">Khách hàng</th>
                                <th class="px-4 py-3 text-center">Ngày tạo</th>
                                <th class="px-4 py-3 text-end">Tổng tiền</th>
                                <th class="px-4 py-3 text-center">Trạng thái</th>
                                <th class="px-4 py-3 text-center">Ngày xóa</th>
                                <th class="px-4 py-3 text-center" style="width:180px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="border-bottom">
                                    <td class="text-center px-4">
                                        <span class="badge bg-light text-dark fs-6">{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                                style="width:45px; height:45px;">
                                                <i class="fa-solid fa-receipt text-warning fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">#{{ $order->order_number }}</div>
                                                <div class="small text-muted">ID: {{ $order->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-semibold text-dark">{{ $order->user->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $order->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="fw-semibold text-dark">{{ $order->created_at->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ $order->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="fw-bold text-primary fs-6">{{ number_format($order->total_amount) }}đ
                                        </div>
                                    </td>
                                    <td class="text-center px-4">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['class' => 'warning', 'text' => 'Chờ xử lý'],
                                                'paid' => ['class' => 'info', 'text' => 'Đã thanh toán'],
                                                'shipped' => ['class' => 'primary', 'text' => 'Đang giao'],
                                                'completed' => ['class' => 'success', 'text' => 'Hoàn thành'],
                                                'cancelled' => ['class' => 'danger', 'text' => 'Đã hủy'],
                                            ];
                                            $status = $order->status->value;
                                            $config = $statusConfig[$status] ?? [
                                                'class' => 'secondary',
                                                'text' => $status,
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $config['class'] }} fs-6 px-3 py-2">
                                            {{ $config['text'] }}
                                        </span>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="small text-muted">
                                            {{ $order->deleted_at->format('d/m/Y') }}<br>
                                            {{ $order->deleted_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-success btn-sm btn-restore"
                                                data-action="{{ route('admin.orders.restore', $order->id) }}"
                                                data-order="{{ $order->order_number }}" data-bs-toggle="tooltip"
                                                title="Khôi phục">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-force-delete"
                                                data-action="{{ route('admin.orders.force-delete', $order->id) }}"
                                                data-order="{{ $order->order_number }}" data-bs-toggle="tooltip"
                                                title="Xóa vĩnh viễn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-trash-can fs-1 d-block mb-3 opacity-50"></i>
                                            <h5>Thùng rác trống</h5>
                                            <p class="mb-0">Không có đơn hàng nào trong thùng rác</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Hiển thị {{ $orders->firstItem() }} - {{ $orders->lastItem() }} trong {{ $orders->total() }}
                            đơn hàng
                        </div>
                        <div>
                            {{ $orders->links('components.pagination') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Khởi tạo Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

            // Khôi phục đơn hàng
            document.querySelectorAll('.btn-restore').forEach(btn => {
                btn.addEventListener('click', function() {
                    const restoreUrl = this.dataset.action;
                    const orderNumber = this.dataset.order;

                    Swal.fire({
                        title: 'Xác nhận khôi phục',
                        html: `
                            <div class="text-center">
                                <i class="fa-solid fa-rotate-left text-success mb-3" style="font-size: 64px;"></i>
                                <p class="mb-2">Bạn có chắc muốn khôi phục đơn hàng</p>
                                <p class="fw-bold text-success fs-5">#${orderNumber}</p>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-solid fa-rotate-left me-2"></i> Khôi phục',
                        cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy',
                        customClass: {
                            confirmButton: 'btn btn-success btn-lg px-4',
                            cancelButton: 'btn btn-secondary btn-lg px-4'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = restoreUrl;
                        }
                    });
                });
            });

            // Xóa vĩnh viễn
            document.querySelectorAll('.btn-force-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const deleteUrl = this.dataset.action;
                    const orderNumber = this.dataset.order;

                    Swal.fire({
                        title: 'Xác nhận xóa vĩnh viễn',
                        html: `
                            <div class="text-center">
                                <i class="fa-solid fa-exclamation-triangle text-danger mb-3" style="font-size: 64px;"></i>
                                <p class="mb-2">Bạn có chắc muốn xóa vĩnh viễn đơn hàng</p>
                                <p class="fw-bold text-danger fs-5 mb-2">#${orderNumber}</p>
                                <div class="alert alert-danger mt-3">
                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                    <small><strong>Cảnh báo:</strong> Hành động này không thể hoàn tác!</small>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-solid fa-trash me-2"></i> Xóa vĩnh viễn',
                        cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy',
                        customClass: {
                            confirmButton: 'btn btn-danger btn-lg px-4',
                            cancelButton: 'btn btn-secondary btn-lg px-4'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = deleteUrl;
                        }
                    });
                });
            });

            // Xóa tất cả
            document.getElementById('emptyTrashBtn')?.addEventListener('click', function() {
                Swal.fire({
                    title: 'Xóa tất cả đơn hàng?',
                    html: `
                        <div class="text-center">
                            <i class="fa-solid fa-dumpster-fire text-danger mb-3" style="font-size: 64px;"></i>
                            <p class="mb-2">Bạn có chắc muốn xóa vĩnh viễn TẤT CẢ đơn hàng trong thùng rác?</p>
                            <div class="alert alert-danger mt-3">
                                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                <small><strong>Cảnh báo:</strong> Hành động này không thể hoàn tác!</small>
                            </div>
                        </div>
                    `,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fa-solid fa-dumpster-fire me-2"></i> Xóa tất cả',
                    cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy',
                    customClass: {
                        confirmButton: 'btn btn-danger btn-lg px-4',
                        cancelButton: 'btn btn-secondary btn-lg px-4'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('admin.orders.empty-trash') }}';
                    }
                });
            });
        });
    </script>
@endpush
