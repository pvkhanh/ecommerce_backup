@extends('layouts.admin')

@section('title', 'Thùng rác - Đơn hàng')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-trash-can text-danger me-2"></i>
                            Thùng rác - Đơn hàng
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                                <li class="breadcrumb-item active">Thùng rác</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100 bg-danger bg-opacity-10">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small mb-1">Tổng đơn</div>
                                <div class="fs-4 fw-bold text-danger">{{ number_format($stats['total']) }}</div>
                            </div>
                            <div class="fs-2 text-danger opacity-50">
                                <i class="fa-solid fa-trash"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small mb-1">Chờ xử lý</div>
                        <div class="fs-5 fw-bold">{{ number_format($stats['pending']) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small mb-1">Đã thanh toán</div>
                        <div class="fs-5 fw-bold">{{ number_format($stats['paid']) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small mb-1">Đang giao</div>
                        <div class="fs-5 fw-bold">{{ number_format($stats['shipped']) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small mb-1">Hoàn thành</div>
                        <div class="fs-5 fw-bold">{{ number_format($stats['completed']) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small mb-1">Đã hủy</div>
                        <div class="fs-5 fw-bold">{{ number_format($stats['cancelled']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert -->
        <div class="alert alert-warning border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-exclamation-triangle fs-3 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Lưu ý quan trọng</h5>
                    <p class="mb-0">
                        Đơn hàng trong thùng rác có thể được khôi phục hoặc xóa vĩnh viễn.
                        <strong>Hành động xóa vĩnh viễn không thể hoàn tác!</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-solid fa-list text-danger me-2"></i>Đơn hàng đã xóa
                        <span class="badge bg-danger fs-6">{{ $orders->total() }} đơn</span>
                    </h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-center" style="width:80px;">#</th>
                                <th class="px-4 py-3">Mã đơn hàng</th>
                                <th class="px-4 py-3">Khách hàng</th>
                                <th class="px-4 py-3 text-center">Ngày xóa</th>
                                <th class="px-4 py-3 text-end">Tổng tiền</th>
                                <th class="px-4 py-3 text-center">Trạng thái</th>
                                <th class="px-4 py-3 text-center" style="width:200px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $order)
                                <tr class="border-bottom">
                                    <td class="text-center px-4">
                                        <span
                                            class="badge bg-light text-dark fs-6">{{ $orders->firstItem() + $index }}</span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                                style="width:45px; height:45px;">
                                                <i class="fa-solid fa-receipt text-danger fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">#{{ $order->order_number }}</div>
                                                <div class="small text-muted">ID: {{ $order->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-semibold text-dark">{{ $order->user->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">
                                            <i class="fa-solid fa-envelope me-1"></i>{{ $order->user->email ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="fw-semibold text-dark">{{ $order->deleted_at->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ $order->deleted_at->format('H:i') }}</div>
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="fw-bold text-primary fs-6">{{ number_format($order->total_amount) }}đ
                                        </div>
                                        @if ($order->shipping_fee > 0)
                                            <div class="small text-muted">Ship: {{ number_format($order->shipping_fee) }}đ
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center px-4">
                                        @php
                                            $statusConfig = [
                                                'pending' => [
                                                    'class' => 'warning',
                                                    'icon' => 'clock',
                                                    'text' => 'Chờ xử lý',
                                                ],
                                                'paid' => [
                                                    'class' => 'info',
                                                    'icon' => 'credit-card',
                                                    'text' => 'Đã thanh toán',
                                                ],
                                                'shipped' => [
                                                    'class' => 'primary',
                                                    'icon' => 'truck',
                                                    'text' => 'Đang giao',
                                                ],
                                                'completed' => [
                                                    'class' => 'success',
                                                    'icon' => 'check-circle',
                                                    'text' => 'Hoàn thành',
                                                ],
                                                'cancelled' => [
                                                    'class' => 'danger',
                                                    'icon' => 'ban',
                                                    'text' => 'Đã hủy',
                                                ],
                                            ];
                                            $status = $order->status->value;
                                            $config = $statusConfig[$status] ?? [
                                                'class' => 'secondary',
                                                'icon' => 'question',
                                                'text' => $status,
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $config['class'] }} fs-6 px-3 py-2">
                                            <i class="fa-solid fa-{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                        </span>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('admin.orders.restore', $order->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm"
                                                    data-bs-toggle="tooltip" title="Khôi phục">
                                                    <i class="fa-solid fa-undo"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-force-delete"
                                                data-action="{{ route('admin.orders.force-delete', $order->id) }}"
                                                data-order="{{ $order->order_number }}" data-bs-toggle="tooltip"
                                                title="Xóa vĩnh viễn">
                                                <i class="fa-solid fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
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
                        <div>{{ $orders->links() }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Initialize Tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

                // Force Delete with SweetAlert2
                document.querySelectorAll('.btn-force-delete').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();

                        const deleteUrl = this.dataset.action;
                        const orderNumber = this.dataset.order;

                        Swal.fire({
                            title: 'Xóa vĩnh viễn?',
                            html: `
                    <div class="text-center">
                        <i class="fa-solid fa-trash-can text-danger mb-3" style="font-size: 64px;"></i>
                        <p class="mb-2">Bạn có chắc chắn muốn xóa vĩnh viễn đơn hàng</p>
                        <p class="fw-bold text-danger fs-5 mb-2">#${orderNumber}</p>
                        <div class="alert alert-danger mt-3">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            <small><strong>Cảnh báo:</strong> Hành động này không thể hoàn tác!</small>
                        </div>
                    </div>
                `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: '<i class="fa-solid fa-trash-alt me-2"></i> Xóa vĩnh viễn',
                            cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy bỏ',
                            reverseButtons: true,
                            width: '600px',
                            customClass: {
                                confirmButton: 'btn btn-danger btn-lg px-4',
                                cancelButton: 'btn btn-secondary btn-lg px-4'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Đang xóa...',
                                    html: `
                            <div class="text-center">
                                <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mb-0">Vui lòng đợi...</p>
                            </div>
                        `,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        const form = document.createElement('form');
                                        form.method = 'POST';
                                        form.action = deleteUrl;

                                        const csrfInput = document.createElement(
                                            'input');
                                        csrfInput.type = 'hidden';
                                        csrfInput.name = '_token';
                                        csrfInput.value = document.querySelector(
                                            'meta[name="csrf-token"]').content;
                                        form.appendChild(csrfInput);

                                        const methodInput = document.createElement(
                                            'input');
                                        methodInput.type = 'hidden';
                                        methodInput.name = '_method';
                                        methodInput.value = 'DELETE';
                                        form.appendChild(methodInput);

                                        document.body.appendChild(form);
                                        form.submit();
                                    }
                                });
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
