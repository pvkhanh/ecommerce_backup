<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-8">
        <!-- Print Button -->
        <div class="mb-4 no-print">
            <button onclick="window.print()"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                <i class="fas fa-print mr-2"></i>In hóa đơn
            </button>
            <button onclick="window.close()"
                class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-lg ml-2">
                <i class="fas fa-times mr-2"></i>Đóng
            </button>
        </div>

        <!-- Invoice -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">HÓA ĐƠN BÁN HÀNG</h1>
                        <p class="text-blue-100">Invoice #{{ $order->order_number }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white text-blue-600 px-6 py-3 rounded-xl inline-block">
                            <i class="fas fa-store text-3xl"></i>
                        </div>
                        <p class="mt-2 text-sm text-blue-100">Ngày: {{ $order->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <!-- Company & Customer Info -->
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <!-- Company Info -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Từ</h3>
                        <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-600">
                            <h4 class="font-bold text-lg text-gray-900 mb-2">Công ty TNHH ABC</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                <i class="fas fa-map-marker-alt text-blue-600 w-4"></i> 123 Đường ABC, Quận XYZ<br>
                                <i class="fas fa-phone text-blue-600 w-4"></i> (024) 1234 5678<br>
                                <i class="fas fa-envelope text-blue-600 w-4"></i> info@company.com<br>
                                <i class="fas fa-globe text-blue-600 w-4"></i> www.company.com
                            </p>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Đến</h3>
                        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-600">
                            <h4 class="font-bold text-lg text-gray-900 mb-2">{{ $order->user->name ?? 'N/A' }}</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                <i class="fas fa-envelope text-blue-600 w-4"></i> {{ $order->user->email ?? 'N/A' }}<br>
                                <i class="fas fa-phone text-blue-600 w-4"></i> {{ $order->user->phone ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                @if ($order->shippingAddress)
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Địa chỉ giao hàng
                        </h3>
                        <div class="bg-amber-50 p-4 rounded-lg border-l-4 border-amber-500">
                            <p class="font-semibold text-gray-900 mb-1">{{ $order->shippingAddress->receiver_name }}</p>
                            <p class="text-gray-700 text-sm">
                                <i class="fas fa-phone text-amber-600 w-4"></i> {{ $order->shippingAddress->phone }}<br>
                                <i class="fas fa-map-marker-alt text-amber-600 w-4"></i>
                                {{ $order->shippingAddress->address }}, {{ $order->shippingAddress->ward }},
                                {{ $order->shippingAddress->district }}, {{ $order->shippingAddress->province }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Order Items Table -->
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Chi tiết đơn hàng</h3>
                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        STT</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Sản phẩm</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Số lượng</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Đơn giá</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($order->orderItems as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4">
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    {{ $item->product->name ?? 'N/A' }}</p>
                                                @if ($item->variant)
                                                    <p class="text-xs text-gray-500 mt-1">{{ $item->variant->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center text-sm text-gray-900 font-medium">
                                            {{ $item->quantity }}</td>
                                        <td class="px-4 py-4 text-right text-sm text-gray-900">
                                            {{ number_format($item->price) }}đ</td>
                                        <td class="px-4 py-4 text-right text-sm font-semibold text-gray-900">
                                            {{ number_format($item->price * $item->quantity) }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="flex justify-end mb-8">
                    <div class="w-80">
                        <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                            <div class="flex justify-between text-gray-700">
                                <span>Tạm tính:</span>
                                <span
                                    class="font-medium">{{ number_format($order->total_amount - $order->shipping_fee) }}đ</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Phí vận chuyển:</span>
                                <span class="font-medium">{{ number_format($order->shipping_fee) }}đ</span>
                            </div>
                            <div class="border-t-2 border-gray-300 pt-3 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Tổng cộng:</span>
                                <span
                                    class="text-2xl font-bold text-blue-600">{{ number_format($order->total_amount) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment & Status Info -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <!-- Payment Info -->
                    <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                        <h4 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-credit-card text-green-600"></i>
                            Thanh toán
                        </h4>
                        @if ($order->payments->count() > 0)
                            @php $payment = $order->payments->first(); @endphp
                            <p class="text-sm text-gray-700">
                                Phương thức: <span
                                    class="font-medium">{{ $payment->payment_method->value ?? 'N/A' }}</span><br>
                                Trạng thái: <span class="font-medium capitalize">{{ $payment->status->value }}</span>
                                @if ($payment->paid_at)
                                    <br>Thời gian: {{ $payment->paid_at->format('d/m/Y H:i') }}
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-500">Chưa có thông tin</p>
                        @endif
                    </div>

                    <!-- Order Status -->
                    <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                        <h4 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-box text-blue-600"></i>
                            Trạng thái đơn hàng
                        </h4>
                        <p class="text-sm text-gray-700">
                            @php
                                $statusText = [
                                    'pending' => 'Chờ xử lý',
                                    'paid' => 'Đã thanh toán',
                                    'shipped' => 'Đang giao hàng',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                ];
                            @endphp
                            <span
                                class="font-medium">{{ $statusText[$order->status->value] ?? $order->status->value }}</span><br>
                            Ngày tạo: {{ $order->created_at->format('d/m/Y H:i') }}
                            @if ($order->completed_at)
                                <br>Hoàn thành: {{ $order->completed_at->format('d/m/Y H:i') }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Notes -->
                @if ($order->customer_note)
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Ghi chú:</h4>
                        <p class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            {{ $order->customer_note }}</p>
                    </div>
                @endif

                <!-- Footer -->
                <div class="border-t-2 border-gray-200 pt-6 mt-8">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-12">Người lập phiếu</p>
                            <p
                                class="text-sm font-semibold text-gray-900 border-t-2 border-gray-300 pt-2 inline-block px-8">
                                (Ký, ghi rõ họ tên)
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-12">Người nhận hàng</p>
                            <p
                                class="text-sm font-semibold text-gray-900 border-t-2 border-gray-300 pt-2 inline-block px-8">
                                (Ký, ghi rõ họ tên)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="mt-8 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-xs text-gray-600 leading-relaxed">
                        <strong>Lưu ý:</strong> Vui lòng kiểm tra kỹ hàng hóa trước khi nhận. Mọi thắc mắc xin liên hệ
                        hotline: (024) 1234 5678
                        hoặc email: support@company.com. Xin cảm ơn quý khách đã tin tưởng sử dụng dịch vụ của chúng
                        tôi!
                    </p>
                </div>
            </div>

            <!-- Invoice Footer -->
            <div class="bg-gray-800 text-white p-6 text-center">
                <p class="text-sm mb-1">Cảm ơn quý khách đã mua hàng!</p>
                <p class="text-xs text-gray-400">Hóa đơn được tạo tự động bởi hệ thống</p>
            </div>
        </div>
    </div>
</body>

</html>
