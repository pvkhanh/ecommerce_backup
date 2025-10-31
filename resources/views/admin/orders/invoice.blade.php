<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }

        .invoice-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            position: relative;
        }

        .invoice-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 20px;
            background: white;
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 0);
        }

        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
        }

        .company-logo {
            font-size: 32px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 36px;
            margin-bottom: 5px;
        }

        .invoice-title p {
            opacity: 0.9;
            font-size: 16px;
        }

        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 30px 40px;
            background: #f8f9fa;
        }

        .meta-block h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 16px;
            text-transform: uppercase;
        }

        .meta-block p {
            margin: 5px 0;
            color: #555;
        }

        .invoice-details {
            padding: 20px 40px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        .detail-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .detail-label {
            color: #888;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }

        .invoice-body {
            padding: 30px 40px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background: #667eea;
            color: white;
        }

        .items-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }

        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .items-table tbody tr:hover {
            background: #f8f9fa;
        }

        .items-table td {
            padding: 15px;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }

        .product-details h4 {
            margin-bottom: 3px;
            font-size: 15px;
        }

        .product-variant {
            color: #888;
            font-size: 13px;
        }

        .quantity-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .totals-section {
            max-width: 400px;
            margin-left: auto;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .total-row:last-child {
            border-bottom: none;
            padding-top: 15px;
            margin-top: 10px;
            border-top: 2px solid #667eea;
        }

        .total-row.grand-total {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }

        .invoice-footer {
            padding: 30px 40px;
            background: #f8f9fa;
            border-top: 3px solid #667eea;
            margin-top: 40px;
        }

        .footer-notes {
            margin-bottom: 20px;
        }

        .footer-notes h4 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .footer-signature {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
        }

        .signature-block {
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin-top: 60px;
            padding-top: 10px;
            font-style: italic;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-paid {
            background: #cfe2ff;
            color: #084298;
        }

        .status-shipped {
            background: #e7d4ff;
            color: #6f42c1;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #842029;
        }

        @media print {
            body {
                background: white;
            }

            .invoice-container {
                box-shadow: none;
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-button no-print">
        🖨️ In hóa đơn
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-logo">
                    🏪 {{ config('app.name', 'Your Shop') }}
                </div>
                <div class="invoice-title">
                    <h1>HÓA ĐƠN</h1>
                    <p>#{{ $order->order_number }}</p>
                </div>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="invoice-meta">
            <div class="meta-block">
                <h3>📍 Thông tin công ty</h3>
                <p><strong>{{ config('app.name', 'Your Shop') }}</strong></p>
                <p>123 Đường ABC, Quận XYZ</p>
                <p>Thành phố Hà Nội, Việt Nam</p>
                <p>📞 Hotline: 1900-xxxx</p>
                <p>✉️ Email: contact@yourshop.com</p>
            </div>

            <div class="meta-block">
                <h3>👤 Thông tin khách hàng</h3>
                <p><strong>{{ $order->user->name ?? 'N/A' }}</strong></p>
                @if ($order->shippingAddress)
                    <p>{{ $order->shippingAddress->address }}</p>
                    <p>{{ $order->shippingAddress->ward }}, {{ $order->shippingAddress->district }}</p>
                    <p>{{ $order->shippingAddress->province }}</p>
                    <p>📞 {{ $order->shippingAddress->phone }}</p>
                @endif
                <p>✉️ {{ $order->user->email ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-item">
                <div class="detail-label">Ngày đặt hàng</div>
                <div class="detail-value">{{ $order->created_at->format('d/m/Y') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Trạng thái</div>
                <div class="detail-value">
                    @php
                        $statusClass = match ($order->status->value) {
                            'completed' => 'status-completed',
                            'paid' => 'status-paid',
                            'shipped' => 'status-shipped',
                            'pending' => 'status-pending',
                            'cancelled' => 'status-cancelled',
                            default => 'status-pending',
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ $order->status->label() }}
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Thanh toán</div>
                <div class="detail-value">
                    @php $payment = $order->payments->first(); @endphp
                    {{ $payment ? $payment->payment_method->label() : 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Invoice Body -->
        <div class="invoice-body">
            <h3 style="color: #667eea; margin-bottom: 20px; font-size: 20px;">📦 Chi tiết đơn hàng</h3>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Sản phẩm</th>
                        <th style="width: 15%; text-align: center;">Đơn giá</th>
                        <th style="width: 15%; text-align: center;">Số lượng</th>
                        <th style="width: 20%;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="product-info">
                                    @if ($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                            alt="{{ $item->product->name }}" class="product-image">
                                    @else
                                        <div class="product-image"
                                            style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                            🖼️
                                        </div>
                                    @endif
                                    <div class="product-details">
                                        <h4>{{ $item->product->name }}</h4>
                                        @if ($item->variant)
                                            <p class="product-variant">{{ $item->variant->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">{{ number_format($item->price) }}đ</td>
                            <td style="text-align: center;">
                                <span class="quantity-badge">{{ $item->quantity }}</span>
                            </td>
                            <td style="text-align: right; font-weight: bold;">
                                {{ number_format($item->price * $item->quantity) }}đ
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals-section">
                <div class="total-row">
                    <span>Tạm tính:</span>
                    <strong>{{ number_format($order->orderItems->sum(fn($item) => $item->price * $item->quantity)) }}đ</strong>
                </div>
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <strong>{{ number_format($order->shipping_fee) }}đ</strong>
                </div>
                <div class="total-row grand-total">
                    <span>TỔNG CỘNG:</span>
                    <strong>{{ number_format($order->total_amount) }}đ</strong>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            @if ($order->customer_note || $order->admin_note)
                <div class="footer-notes">
                    <h4>📝 Ghi chú</h4>
                    @if ($order->customer_note)
                        <p><strong>Khách hàng:</strong> {{ $order->customer_note }}</p>
                    @endif
                    @if ($order->admin_note)
                        <p><strong>Admin:</strong> {{ $order->admin_note }}</p>
                    @endif
                </div>
            @endif

            <p style="color: #888; font-size: 13px; margin-bottom: 10px;">
                ⭐ Cảm ơn quý khách đã mua hàng tại {{ config('app.name') }}!
            </p>
            <p style="color: #888; font-size: 13px;">
                Mọi thắc mắc xin vui lòng liên hệ: hotline 1900-xxxx hoặc email: contact@yourshop.com
            </p>

            <div class="footer-signature">
                <div class="signature-block">
                    <strong>Người lập phiếu</strong>
                    <div class="signature-line">
                        (Ký và ghi rõ họ tên)
                    </div>
                </div>
                <div class="signature-block">
                    <strong>Người nhận hàng</strong>
                    <div class="signature-line">
                        (Ký và ghi rõ họ tên)
                    </div>
                </div>
            </div>

            <p style="text-align: center; margin-top: 30px; color: #888; font-size: 12px;">
                Hóa đơn được in tự động từ hệ thống vào {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = () => window.print();
    </script>
</body>

</html>
