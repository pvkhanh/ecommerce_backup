<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payments', 'orderItems'])->latest();

        // Search
        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        // Date range
        if ($from = $request->from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->to) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Amount range
        if ($minAmount = $request->min_amount) {
            $query->where('total_amount', '>=', $minAmount);
        }
        if ($maxAmount = $request->max_amount) {
            $query->where('total_amount', '<=', $maxAmount);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Statistics
        $statsQuery = Order::query();
        if ($from) $statsQuery->whereDate('created_at', '>=', $from);
        if ($to) $statsQuery->whereDate('created_at', '<=', $to);

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('status', OrderStatus::Pending)->count(),
            'paid' => (clone $statsQuery)->where('status', OrderStatus::Paid)->count(),
            'shipped' => (clone $statsQuery)->where('status', OrderStatus::Shipped)->count(),
            'completed' => (clone $statsQuery)->where('status', OrderStatus::Completed)->count(),
            'cancelled' => (clone $statsQuery)->where('status', OrderStatus::Cancelled)->count(),
            'total_revenue' => (clone $statsQuery)->where('status', OrderStatus::Completed)->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with([
            'user',
            'orderItems.product',
            'orderItems.variant',
            'shippingAddress',
            'payments'
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $order = Order::with([
            'user',
            'orderItems.product',
            'orderItems.variant',
            'shippingAddress',
            'payments'
        ])->findOrFail($id);

        $statuses = OrderStatus::cases();

        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    /**
     * Cập nhật đơn hàng
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', OrderStatus::values()),
            'admin_note' => 'nullable|string|max:500',
            'shipping_fee' => 'nullable|numeric|min:0',
            'customer_note' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            $updateData = [
                'status' => OrderStatus::from($validated['status']),
                'admin_note' => $validated['admin_note'] ?? null,
                'customer_note' => $validated['customer_note'] ?? $order->customer_note,
            ];

            if (isset($validated['shipping_fee'])) {
                $updateData['shipping_fee'] = $validated['shipping_fee'];
                // Recalculate total
                $subtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
                $updateData['total_amount'] = $subtotal + $validated['shipping_fee'];
            }

            // Update timestamp based on status
            match (OrderStatus::from($validated['status'])) {
                OrderStatus::Shipped => $updateData['shipped_at'] = now(),
                OrderStatus::Completed => $updateData['completed_at'] = now(),
                OrderStatus::Cancelled => $updateData['cancelled_at'] = now(),
                default => null,
            };

            $order->update($updateData);

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $id)
                ->with('success', 'Cập nhật đơn hàng thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa đơn hàng (soft delete)
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Chỉ cho phép xóa đơn đã hủy hoặc hoàn thành
            if (!in_array($order->status, [OrderStatus::Cancelled, OrderStatus::Completed])) {
                return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy hoặc hoàn thành');
            }

            $order->delete();

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Đã chuyển đơn hàng vào thùng rác');

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách đơn đã xóa
     */
    public function trashed()
    {
        $orders = Order::onlyTrashed()
            ->with(['user', 'payments'])
            ->latest('deleted_at')
            ->paginate(15);

        $stats = [
            'total' => Order::onlyTrashed()->count(),
            'pending' => Order::onlyTrashed()->where('status', OrderStatus::Pending)->count(),
            'paid' => Order::onlyTrashed()->where('status', OrderStatus::Paid)->count(),
            'shipped' => Order::onlyTrashed()->where('status', OrderStatus::Shipped)->count(),
            'completed' => Order::onlyTrashed()->where('status', OrderStatus::Completed)->count(),
            'cancelled' => Order::onlyTrashed()->where('status', OrderStatus::Cancelled)->count(),
        ];

        return view('admin.orders.trashed', compact('orders', 'stats'));
    }

    /**
     * Khôi phục đơn hàng
     */
    public function restore($id)
    {
        try {
            $order = Order::onlyTrashed()->findOrFail($id);
            $order->restore();

            return redirect()
                ->route('admin.orders.trashed')
                ->with('success', 'Khôi phục đơn hàng thành công');

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::onlyTrashed()->findOrFail($id);

            // Delete related records
            $order->orderItems()->forceDelete();
            $order->shippingAddress()->forceDelete();
            $order->payments()->forceDelete();

            $order->forceDelete();

            DB::commit();

            return redirect()
                ->route('admin.orders.trashed')
                ->with('success', 'Đã xóa vĩnh viễn đơn hàng');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái nhanh (AJAX)
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', OrderStatus::values()),
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            $updateData = [
                'status' => OrderStatus::from($validated['status'])
            ];

            // Update timestamp
            match (OrderStatus::from($validated['status'])) {
                OrderStatus::Shipped => $updateData['shipped_at'] = now(),
                OrderStatus::Completed => $updateData['completed_at'] = now(),
                OrderStatus::Cancelled => $updateData['cancelled_at'] = now(),
                default => null,
            };

            $order->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'status' => $order->status->label(),
                'color' => $order->status->color(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Xác nhận thanh toán
     */
    public function confirmPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'transaction_id' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $payment = $order->payments()->first();

            if ($payment) {
                $payment->update([
                    'status' => PaymentStatus::Success,
                    'transaction_id' => $validated['transaction_id'] ?? $payment->transaction_id,
                    'paid_at' => now(),
                ]);

                $order->update([
                    'status' => OrderStatus::Paid,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Xác nhận thanh toán thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            // Chỉ cho phép hủy đơn ở trạng thái Pending hoặc Paid
            if (!in_array($order->status, [OrderStatus::Pending, OrderStatus::Paid])) {
                throw new \Exception('Không thể hủy đơn hàng ở trạng thái này');
            }

            $order->update([
                'status' => OrderStatus::Cancelled,
                'cancelled_at' => now(),
                'admin_note' => $validated['reason'],
            ]);

            // Restore product stock
            foreach ($order->orderItems as $item) {
                if ($item->variant_id) {
                    $item->variant->increment('stock', $item->quantity);
                } else {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            DB::commit();

            return back()->with('success', 'Đã hủy đơn hàng thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * In hóa đơn
     */
    public function invoice($id)
    {
        $order = Order::with([
            'user',
            'orderItems.product',
            'orderItems.variant',
            'shippingAddress',
            'payments'
        ])->findOrFail($id);

        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Export Excel
     */
    public function export(Request $request)
    {
        try {
            $query = Order::with(['user', 'payments', 'orderItems']);

            // Apply filters
            if ($status = $request->status) {
                $query->where('status', $status);
            }
            if ($from = $request->from) {
                $query->whereDate('created_at', '>=', $from);
            }
            if ($to = $request->to) {
                $query->whereDate('created_at', '<=', $to);
            }

            $orders = $query->get();

            // Create CSV
            $filename = 'orders_' . date('YmdHis') . '.csv';
            $handle = fopen('php://temp', 'r+');

            // BOM for UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($handle, [
                'Mã đơn hàng',
                'Khách hàng',
                'Email',
                'Số điện thoại',
                'Ngày đặt',
                'Tổng tiền',
                'Phí ship',
                'Trạng thái',
                'Thanh toán',
            ]);

            // Data
            foreach ($orders as $order) {
                $payment = $order->payments->first();
                fputcsv($handle, [
                    $order->order_number,
                    $order->user->name ?? 'N/A',
                    $order->user->email ?? 'N/A',
                    $order->shippingAddress->phone ?? 'N/A',
                    $order->created_at->format('d/m/Y H:i'),
                    number_format($order->total_amount),
                    number_format($order->shipping_fee),
                    $order->status->label(),
                    $payment ? $payment->status->label() : 'N/A',
                ]);
            }

            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tạo đơn hàng mới (Admin)
     */
    public function create()
    {
        $users = \App\Models\User::where('role', 'customer')->get();
        $products = Product::where('status', 'active')->get();

        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Lưu đơn hàng mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'shipping_fee' => 'required|numeric|min:0',
            'customer_note' => 'nullable|string|max:500',
            'admin_note' => 'nullable|string|max:500',
            'payment_method' => 'required|string',
            'shipping_address' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total
            $subtotal = collect($validated['items'])->sum(fn($item) => $item['price'] * $item['quantity']);
            $total = $subtotal + $validated['shipping_fee'];

            // Create order
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'order_number' => $this->generateOrderNumber(),
                'total_amount' => $total,
                'shipping_fee' => $validated['shipping_fee'],
                'customer_note' => $validated['customer_note'] ?? null,
                'admin_note' => $validated['admin_note'] ?? null,
                'status' => OrderStatus::Pending,
            ]);

            // Create order items
            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Decrease stock
                if (isset($item['variant_id'])) {
                    ProductVariant::find($item['variant_id'])->decrement('stock', $item['quantity']);
                } else {
                    Product::find($item['product_id'])->decrement('stock', $item['quantity']);
                }
            }

            // Create shipping address
            $order->shippingAddress()->create($validated['shipping_address']);

            // Create payment
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $total,
                'status' => PaymentStatus::Pending,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('success', 'Tạo đơn hàng thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Generate order number
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD' . date('Ymd') . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}