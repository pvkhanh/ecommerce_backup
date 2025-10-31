{{-- @extends('admin.layouts.app')

@section('title', 'Quản lý Đơn hàng')

@section('content')
    <div class="container-fluid px-4 py-6">
        <!-- Header với Stats -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Quản lý Đơn hàng</h1>
                    <p class="text-gray-600 mt-1">Theo dõi và quản lý tất cả đơn hàng</p>
                </div>
                <button onclick="window.print()" class="btn-primary">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
                <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-content">
                        <p class="stat-label">Tổng đơn</p>
                        <p class="stat-value">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-yellow-500 to-yellow-600">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-content">
                        <p class="stat-label">Chờ xử lý</p>
                        <p class="stat-value">{{ number_format($stats['pending']) }}</p>
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
                    <div class="stat-icon"><i class="fas fa-credit-card"></i></div>
                    <div class="stat-content">
                        <p class="stat-label">Đã thanh toán</p>
                        <p class="stat-value">{{ number_format($stats['paid']) }}</p>
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-indigo-500 to-indigo-600">
                    <div class="stat-icon"><i class="fas fa-truck"></i></div>
                    <div class="stat-content">
                        <p class="stat-label">Đang giao</p>
                        <p class="stat-value">{{ number_format($stats['shipped']) }}</p>
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-content">
                        <p class="stat-label">Hoàn thành</p>
                        <p class="stat-value">{{ number_format($stats['completed']) }}</p>
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-red-500 to-red-600">
                    <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-content">
                        <p class="stat-label">Đã hủy</p>
                        <p class="stat-value">{{ number_format($stats['cancelled']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 mb-1">Tổng doanh thu</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_revenue']) }}đ</p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã đơn hàng..."
                            class="form-input">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý
                            </option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán
                            </option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao hàng
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-input">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-input">
                    </div>

                    <!-- Min Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền tối thiểu</label>
                        <input type="number" name="min_amount" value="{{ request('min_amount') }}" placeholder="0"
                            class="form-input">
                    </div>

                    <!-- Max Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền tối đa</label>
                        <input type="number" name="max_amount" value="{{ request('max_amount') }}" placeholder="10000000"
                            class="form-input">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-2 md:col-span-2">
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-search mr-2"></i>Lọc
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Mã đơn hàng
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Khách hàng
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ngày đặt
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tổng tiền
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Thanh toán
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-receipt text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">#{{ $order->order_number }}</p>
                                            <p class="text-xs text-gray-500">ID: {{ $order->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->user->email ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ number_format($order->total_amount) }}đ</p>
                                    @if ($order->shipping_fee > 0)
                                        <p class="text-xs text-gray-500">Ship: {{ number_format($order->shipping_fee) }}đ
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $payment = $order->payments->first();
                                        $paymentStatus = $payment ? $payment->status->value : 'pending';
                                    @endphp
                                    @if ($paymentStatus === 'success')
                                        <span class="payment-badge payment-success">
                                            <i class="fas fa-check-circle mr-1"></i>Đã thanh toán
                                        </span>
                                    @elseif($paymentStatus === 'pending')
                                        <span class="payment-badge payment-pending">
                                            <i class="fas fa-clock mr-1"></i>Chờ thanh toán
                                        </span>
                                    @else
                                        <span class="payment-badge payment-failed">
                                            <i class="fas fa-times-circle mr-1"></i>Thất bại
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'paid' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                            'completed' => 'bg-green-100 text-green-800 border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                        $statusText = [
                                            'pending' => 'Chờ xử lý',
                                            'paid' => 'Đã thanh toán',
                                            'shipped' => 'Đang giao hàng',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                        ];
                                        $status = $order->status->value;
                                    @endphp
                                    <span
                                        class="status-badge {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusText[$status] ?? $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="action-btn action-btn-view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.invoice', $order->id) }}"
                                            class="action-btn action-btn-print" title="In hóa đơn" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-delete" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                        <p class="text-gray-500 text-lg">Không có đơn hàng nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .stat-card {
            @apply rounded-xl p-5 text-white shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            @apply text-3xl opacity-80;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            @apply text-xs font-medium opacity-90 mb-1;
        }

        .stat-value {
            @apply text-2xl font-bold;
        }

        .form-input,
        .form-select {
            @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors;
        }

        .btn-primary {
            @apply px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md;
        }

        .btn-secondary {
            @apply px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors;
        }

        .status-badge {
            @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border;
        }

        .payment-badge {
            @apply inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium;
        }

        .payment-success {
            @apply bg-green-50 text-green-700 border border-green-200;
        }

        .payment-pending {
            @apply bg-yellow-50 text-yellow-700 border border-yellow-200;
        }

        .payment-failed {
            @apply bg-red-50 text-red-700 border border-red-200;
        }

        .action-btn {
            @apply w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-200 border;
        }

        .action-btn-view {
            @apply bg-blue-50 text-blue-600 border-blue-200 hover:bg-blue-600 hover:text-white hover:shadow-md;
        }

        .action-btn-print {
            @apply bg-purple-50 text-purple-600 border-purple-200 hover:bg-purple-600 hover:text-white hover:shadow-md;
        }

        .action-btn-delete {
            @apply bg-red-50 text-red-600 border-red-200 hover:bg-red-600 hover:text-white hover:shadow-md;
        }
    </style>
@endsection --}}




{{-- @extends('admin.layouts.app')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header với Stats -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Quản lý Đơn hàng</h1>
                <p class="text-gray-600 mt-1">Theo dõi và quản lý tất cả đơn hàng</p>
            </div>
            <button onclick="window.print()" class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
            <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
                <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-content">
                    <p class="stat-label">Tổng đơn</p>
                    <p class="stat-value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            
            <div class="stat-card bg-gradient-to-br from-yellow-500 to-yellow-600">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-content">
                    <p class="stat-label">Chờ xử lý</p>
                    <p class="stat-value">{{ number_format($stats['pending']) }}</p>
                </div>
            </div>
            
            <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
                <div class="stat-icon"><i class="fas fa-credit-card"></i></div>
                <div class="stat-content">
                    <p class="stat-label">Đã thanh toán</p>
                    <p class="stat-value">{{ number_format($stats['paid']) }}</p>
                </div>
            </div>

            <div class="stat-card bg-gradient-to-br from-indigo-500 to-indigo-600">
                <div class="stat-icon"><i class="fas fa-truck"></i></div>
                <div class="stat-content">
                    <p class="stat-label">Đang giao</p>
                    <p class="stat-value">{{ number_format($stats['shipped']) }}</p>
                </div>
            </div>
            
            <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-content">
                    <p class="stat-label">Hoàn thành</p>
                    <p class="stat-value">{{ number_format($stats['completed']) }}</p>
                </div>
            </div>
            
            <div class="stat-card bg-gradient-to-br from-red-500 to-red-600">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-content">
                    <p class="stat-label">Đã hủy</p>
                    <p class="stat-value">{{ number_format($stats['cancelled']) }}</p>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 mb-1">Tổng doanh thu</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_revenue']) }}đ</p>
                </div>
                <div class="text-5xl opacity-20">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Mã đơn hàng..." class="form-input">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-input">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-input">
                </div>

                <!-- Min Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền tối thiểu</label>
                    <input type="number" name="min_amount" value="{{ request('min_amount') }}" 
                           placeholder="0" class="form-input">
                </div>

                <!-- Max Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền tối đa</label>
                    <input type="number" name="max_amount" value="{{ request('max_amount') }}" 
                           placeholder="10000000" class="form-input">
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>Lọc
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Mã đơn hàng
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Khách hàng
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Ngày đặt
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tổng tiền
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Thanh toán
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-receipt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">#{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $order->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->email ?? 'N/A' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold text-gray-900">{{ number_format($order->total_amount) }}đ</p>
                            @if ($order->shipping_fee > 0)
                            <p class="text-xs text-gray-500">Ship: {{ number_format($order->shipping_fee) }}đ</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $payment = $order->payments->first();
                                $paymentStatus = $payment ? $payment->status->value : 'pending';
                            @endphp
                            @if ($paymentStatus === 'success')
                                <span class="payment-badge payment-success">
                                    <i class="fas fa-check-circle mr-1"></i>Đã thanh toán
                                </span>
                            @elseif($paymentStatus === 'pending')
                                <span class="payment-badge payment-pending">
                                    <i class="fas fa-clock mr-1"></i>Chờ thanh toán
                                </span>
                            @else
                                <span class="payment-badge payment-failed">
                                    <i class="fas fa-times-circle mr-1"></i>Thất bại
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'paid' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $statusText = [
                                    'pending' => 'Chờ xử lý',
                                    'paid' => 'Đã thanh toán',
                                    'shipped' => 'Đang giao hàng',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                ];
                                $status = $order->status->value;
                            @endphp
                            <span class="status-badge {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusText[$status] ?? $status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="action-btn action-btn-view" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                                   class="action-btn action-btn-print" title="In hóa đơn" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" 
                                      method="POST" class="inline" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Không có đơn hàng nào</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.stat-card {
    @apply rounded-xl p-5 text-white shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    @apply text-3xl opacity-80;
}

.stat-content {
    flex: 1;
}

.stat-label {
    @apply text-xs font-medium opacity-90 mb-1;
}

.stat-value {
    @apply text-2xl font-bold;
}

.form-input, .form-select {
    @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors;
}

.btn-primary {
    @apply px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md;
}

.btn-secondary {
    @apply px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors;
}

.status-badge {
    @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border;
}

.payment-badge {
    @apply inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium;
}

.payment-success {
    @apply bg-green-50 text-green-700 border border-green-200;
}

.payment-pending {
    @apply bg-yellow-50 text-yellow-700 border border-yellow-200;
}

.payment-failed {
    @apply bg-red-50 text-red-700 border border-red-200;
}

.action-btn {
    @apply w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-200 border;
}

.action-btn-view {
    @apply bg-blue-50 text-blue-600 border-blue-200 hover:bg-blue-600 hover:text-white hover:shadow-md;
}

.action-btn-print {
    @apply bg-purple-50 text-purple-600 border-purple-200 hover:bg-purple-600 hover:text-white hover:shadow-md;
}

.action-btn-delete {
    @apply bg-red-50 text-red-600 border-red-200 hover:bg-red-600 hover:text-white hover:shadow-md;
}
</style>
@endsection --}}




@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fa-solid fa-shopping-cart text-primary me-2"></i>
                            Quản lý Đơn hàng
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Đơn hàng</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.orders.trashed') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fa-solid fa-trash-arrow-up me-2"></i> Thùng rác
                        </a>
                        <button onclick="window.print()" class="btn btn-success btn-lg shadow-sm">
                            <i class="fa-solid fa-file-export me-2"></i> Xuất Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 small">Tổng đơn</h6>
                                <h4 class="fw-bold mb-0">{{ number_format($stats['total']) }}</h4>
                            </div>
                            <div class="fs-2 opacity-50">
                                <i class="fa-solid fa-shopping-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 small">Chờ xử lý</h6>
                                <h4 class="fw-bold mb-0">{{ number_format($stats['pending']) }}</h4>
                            </div>
                            <div class="fs-2 opacity-50">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-info text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 small">Đã thanh toán</h6>
                                <h4 class="fw-bold mb-0">{{ number_format($stats['paid']) }}</h4>
                            </div>
                            <div class="fs-2 opacity-50">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-purple text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 small">Đang giao</h6>
                                <h4 class="fw-bold mb-0">{{ number_format($stats['shipped']) }}</h4>
                            </div>
                            <div class="fs-2 opacity-50">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-success text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 small">Hoàn thành</h6>
                                <h4 class="fw-bold mb-0">{{ number_format($stats['completed']) }}</h4>
                            </div>
                            <div class="fs-2 opacity-50">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient-danger text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 small">Đã hủy</h6>
                                <h4 class="fw-bold mb-0">{{ number_format($stats['cancelled']) }}</h4>
                            </div>
                            <div class="fs-2 opacity-50">
                                <i class="fa-solid fa-ban"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="card border-0 shadow-sm mb-4 bg-gradient-revenue text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">
                            <i class="fa-solid fa-chart-line me-2"></i>Tổng doanh thu
                        </h6>
                        <h2 class="fw-bold mb-0">{{ number_format($stats['total_revenue']) }}đ</h2>
                        <p class="mb-0 mt-2 small text-white-50">Từ các đơn hàng đã hoàn thành</p>
                    </div>
                    <div class="fs-1 opacity-20">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-filter text-primary me-2"></i>Bộ lọc
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.orders.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-magnifying-glass text-muted me-1"></i> Tìm kiếm
                            </label>
                            <input type="text" name="search" class="form-control form-control-lg"
                                placeholder="Mã đơn hàng..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-list-check text-muted me-1"></i> Trạng thái
                            </label>
                            <select name="status" class="form-select form-select-lg">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý
                                </option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán
                                </option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn
                                    thành</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-calendar text-muted me-1"></i> Từ ngày
                            </label>
                            <input type="date" name="from" class="form-control form-control-lg"
                                value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-calendar-check text-muted me-1"></i> Đến ngày
                            </label>
                            <input type="date" name="to" class="form-control form-control-lg"
                                value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-fill">
                                <i class="fa-solid fa-filter me-2"></i> Lọc
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fa-solid fa-rotate-right"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-solid fa-list text-primary me-2"></i>Danh sách đơn hàng
                        <span class="badge bg-primary fs-6">{{ $orders->total() }} đơn</span>
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
                                <th class="px-4 py-3 text-center">Ngày đặt</th>
                                <th class="px-4 py-3 text-end">Tổng tiền</th>
                                <th class="px-4 py-3 text-center">Thanh toán</th>
                                <th class="px-4 py-3 text-center">Trạng thái</th>
                                <th class="px-4 py-3 text-center" style="width:200px;">Hành động</th>
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
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                                style="width:45px; height:45px;">
                                                <i class="fa-solid fa-receipt text-primary fs-5"></i>
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
                                        <div class="fw-semibold text-dark">{{ $order->created_at->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ $order->created_at->format('H:i') }}</div>
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
                                            $payment = $order->payments->first();
                                            $paymentStatus = $payment ? $payment->status->value : 'pending';
                                        @endphp
                                        @if ($paymentStatus === 'success')
                                            <span class="badge bg-success fs-6 px-3 py-2">
                                                <i class="fa-solid fa-check-circle me-1"></i>Đã thanh toán
                                            </span>
                                        @elseif($paymentStatus === 'pending')
                                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                                <i class="fa-solid fa-clock me-1"></i>Chờ thanh toán
                                            </span>
                                        @else
                                            <span class="badge bg-danger fs-6 px-3 py-2">
                                                <i class="fa-solid fa-times-circle me-1"></i>Thất bại
                                            </span>
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
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip"
                                                title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip"
                                                title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
                                                class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip"
                                                title="In hóa đơn">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                data-action="{{ route('admin.orders.destroy', $order->id) }}"
                                                data-order="{{ $order->order_number }}" data-bs-toggle="tooltip"
                                                title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                                            <h5>Không có đơn hàng nào</h5>
                                            <p class="mb-0">Thử thay đổi bộ lọc hoặc kiểm tra lại</p>
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

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f7b733 0%, #fc4a1a 100%) !important;
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%) !important;
        }

        .bg-gradient-revenue {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-group .btn {
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Khởi tạo Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

            // Xóa đơn hàng với SweetAlert2
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const deleteUrl = this.dataset.action;
                    const orderNumber = this.dataset.order;

                    Swal.fire({
                        title: 'Xác nhận xóa',
                        html: `
                            <div class="text-center">
                                <i class="fa-solid fa-box-open text-warning mb-3" style="font-size: 64px;"></i>
                                <p class="mb-2">Bạn có chắc chắn muốn xóa đơn hàng</p>
                                <p class="fw-bold text-warning fs-5 mb-2">#${orderNumber}</p>
                                <div class="alert alert-info mt-3">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    <small>Đơn hàng sẽ được chuyển vào thùng rác, có thể khôi phục sau.</small>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ffc107',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-solid fa-trash me-2"></i> Xóa',
                        cancelButtonText: '<i class="fa-solid fa-times me-2"></i> Hủy bỏ',
                        reverseButtons: true,
                        width: '600px',
                        customClass: {
                            confirmButton: 'btn btn-warning btn-lg px-4',
                            cancelButton: 'btn btn-secondary btn-lg px-4'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Đang xóa...',
                                html: `
                                    <div class="text-center">
                                        <div class="spinner-border text-warning mb-3" role="status" style="width: 3rem; height: 3rem;">
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
