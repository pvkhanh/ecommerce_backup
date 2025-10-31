<?php

//Bản gốc
// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Repositories\Contracts\OrderRepositoryInterface;
// use App\Repositories\Contracts\OrderItemRepositoryInterface;
// use App\Repositories\Contracts\PaymentRepositoryInterface;
// use App\Enums\OrderStatus;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

// class OrderController extends Controller
// {
//     public function __construct(
//         private OrderRepositoryInterface $orderRepo,
//         private OrderItemRepositoryInterface $orderItemRepo,
//         private PaymentRepositoryInterface $paymentRepo
//     ) {
//     }

//     /**
//      * Hiển thị form tạo đơn hàng mới
//      */
//     public function create()
//     {
//         $users = \App\Models\User::where('role', 'buyer')->get();
//         // $products = \App\Models\Product::where('is_active', true)->get();

//         $products = \App\Models\Product::where('status', true)->get();

//         return view('admin.orders.create', compact('users', 'products'));
//     }

//     /**
//      * Lưu đơn hàng mới
//      */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'user_id' => 'required|exists:users,id',
//             'status' => 'required|in:pending,paid,shipped,completed,cancelled',
//             'shipping_fee' => 'required|numeric|min:0',
//             'total_amount' => 'required|numeric|min:0',
//             'receiver_name' => 'required|string|max:255',
//             'phone' => 'required|string|max:20',
//             'address' => 'required|string',
//             'province' => 'required|string|max:100',
//             'district' => 'required|string|max:100',
//             'ward' => 'required|string|max:100',
//             'items' => 'required|array|min:1',
//             'items.*.product_id' => 'required|exists:products,id',
//             'items.*.quantity' => 'required|integer|min:1',
//             'items.*.price' => 'required|numeric|min:0',
//         ]);

//         try {
//             DB::beginTransaction();

//             // Tạo order number
//             $orderNumber = 'ORD' . date('YmdHis') . rand(100, 999);

//             // Tạo đơn hàng
//             $order = $this->orderRepo->create([
//                 'user_id' => $request->user_id,
//                 'order_number' => $orderNumber,
//                 'status' => $request->status,
//                 'shipping_fee' => $request->shipping_fee,
//                 'total_amount' => $request->total_amount,
//                 'admin_note' => $request->admin_note,
//             ]);

//             // Tạo shipping address
//             $order->shippingAddress()->create([
//                 'receiver_name' => $request->receiver_name,
//                 'phone' => $request->phone,
//                 'address' => $request->address,
//                 'province' => $request->province,
//                 'district' => $request->district,
//                 'ward' => $request->ward,
//             ]);

//             // Tạo order items
//             foreach ($request->items as $item) {
//                 $order->orderItems()->create([
//                     'product_id' => $item['product_id'],
//                     'quantity' => $item['quantity'],
//                     'price' => $item['price'],
//                 ]);
//             }

//             DB::commit();

//             return redirect()->route('admin.orders.show', $order->id)
//                 ->with('success', 'Tạo đơn hàng thành công!');
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return redirect()->back()
//                 ->withInput()
//                 ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Hiển thị form chỉnh sửa đơn hàng
//      */
//     public function edit($id)
//     {
//         $order = $this->orderRepo->newQuery()
//             ->with(['user', 'shippingAddress', 'orderItems', 'payments'])
//             ->findOrFail($id);

//         return view('admin.orders.edit', compact('order'));
//     }

//     /**
//      * Cập nhật đơn hàng
//      */
//     public function update(Request $request, $id)
//     {
//         $request->validate([
//             'status' => 'required|in:pending,paid,shipped,completed,cancelled',
//             'total_amount' => 'required|numeric|min:0',
//             'shipping_fee' => 'required|numeric|min:0',
//         ]);

//         try {
//             $data = [
//                 'status' => $request->status,
//                 'total_amount' => $request->total_amount,
//                 'shipping_fee' => $request->shipping_fee,
//                 'customer_note' => $request->customer_note,
//                 'admin_note' => $request->admin_note,
//             ];

//             // Cập nhật timestamp
//             if ($request->status === 'completed' && !$this->orderRepo->find($id)->completed_at) {
//                 $data['completed_at'] = now();
//             } elseif ($request->status === 'cancelled' && !$this->orderRepo->find($id)->cancelled_at) {
//                 $data['cancelled_at'] = now();
//             }

//             $this->orderRepo->update($id, $data);

//             return redirect()->route('admin.orders.show', $id)
//                 ->with('success', 'Cập nhật đơn hàng thành công!');
//         } catch (\Exception $e) {
//             return redirect()->back()
//                 ->withInput()
//                 ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Hiển thị danh sách đơn hàng
//      */
//     public function index(Request $request)
//     {
//         $query = $this->orderRepo->newQuery()
//             ->with(['user', 'shippingAddress', 'payments']);

//         // Lọc theo status
//         if ($request->filled('status')) {
//             $query->where('status', $request->status);
//         }

//         // Lọc theo user
//         if ($request->filled('user_id')) {
//             $query->where('user_id', $request->user_id);
//         }

//         // Lọc theo khoảng thời gian
//         if ($request->filled('from')) {
//             $query->whereDate('created_at', '>=', $request->from);
//         }
//         if ($request->filled('to')) {
//             $query->whereDate('created_at', '<=', $request->to);
//         }

//         // Lọc theo số tiền
//         if ($request->filled('min_amount')) {
//             $query->where('total_amount', '>=', $request->min_amount);
//         }
//         if ($request->filled('max_amount')) {
//             $query->where('total_amount', '<=', $request->max_amount);
//         }

//         // Tìm kiếm theo order number
//         if ($request->filled('search')) {
//             $query->where('order_number', 'like', '%' . $request->search . '%');
//         }

//         $orders = $query->latest()->paginate(15)->withQueryString();

//         // Thống kê
//         $stats = [
//             'total' => $this->orderRepo->newQuery()->count(),
//             'pending' => $this->orderRepo->newQuery()->where('status', 'pending')->count(),
//             'paid' => $this->orderRepo->newQuery()->where('status', 'paid')->count(),
//             'shipped' => $this->orderRepo->newQuery()->where('status', 'shipped')->count(),
//             'completed' => $this->orderRepo->newQuery()->where('status', 'completed')->count(),
//             'cancelled' => $this->orderRepo->newQuery()->where('status', 'cancelled')->count(),
//             'total_revenue' => $this->orderRepo->newQuery()
//                 ->where('status', 'completed')
//                 ->sum('total_amount'),
//         ];

//         return view('admin.orders.index', compact('orders', 'stats'));
//     }

//     /**
//      * Hiển thị chi tiết đơn hàng
//      */
//     public function show($id)
//     {
//         $order = $this->orderRepo->newQuery()
//             ->with(['user', 'shippingAddress', 'orderItems.product', 'orderItems.variant', 'payments'])
//             ->findOrFail($id);

//         return view('admin.orders.show', compact('order'));
//     }

//     /**
//      * Cập nhật trạng thái đơn hàng
//      */
//     public function updateStatus(Request $request, $id)
//     {
//         $request->validate([
//             'status' => 'required|string|in:pending,paid,shipped,completed,cancelled',
//             'admin_note' => 'nullable|string|max:500'
//         ]);

//         try {
//             DB::beginTransaction();

//             $data = ['status' => $request->status];

//             // Cập nhật timestamp tương ứng
//             if ($request->status === 'completed') {
//                 $data['completed_at'] = now();
//             } elseif ($request->status === 'cancelled') {
//                 $data['cancelled_at'] = now();
//             }

//             if ($request->filled('admin_note')) {
//                 $data['admin_note'] = $request->admin_note;
//             }

//             $this->orderRepo->update($id, $data);

//             DB::commit();

//             return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Xóa đơn hàng (soft delete)
//      */
//     public function destroy($id)
//     {
//         try {
//             $this->orderRepo->delete($id);
//             return redirect()->route('admin.orders.index')
//                 ->with('success', 'Đơn hàng đã được chuyển vào thùng rác!');
//         } catch (\Exception $e) {
//             return redirect()->back()
//                 ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Hiển thị thùng rác
//      */
//     public function trashed()
//     {
//         $orders = $this->orderRepo->newQuery()
//             ->onlyTrashed()
//             ->with(['user'])
//             ->latest('deleted_at')
//             ->paginate(15);

//         return view('admin.orders.trashed', compact('orders'));
//     }

//     /**
//      * Khôi phục đơn hàng
//      */
//     public function restore($id)
//     {
//         try {
//             $order = $this->orderRepo->newQuery()->onlyTrashed()->findOrFail($id);
//             $order->restore();

//             return redirect()->route('admin.orders.trashed')
//                 ->with('success', 'Khôi phục đơn hàng thành công!');
//         } catch (\Exception $e) {
//             return redirect()->back()
//                 ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Xóa vĩnh viễn đơn hàng
//      */
//     public function forceDelete($id)
//     {
//         try {
//             $order = $this->orderRepo->newQuery()->onlyTrashed()->findOrFail($id);

//             // Xóa các bản ghi liên quan
//             $order->orderItems()->forceDelete();
//             $order->payments()->forceDelete();
//             $order->shippingAddress()->forceDelete();

//             // Xóa vĩnh viễn đơn hàng
//             $order->forceDelete();

//             return redirect()->route('admin.orders.trashed')
//                 ->with('success', 'Xóa vĩnh viễn đơn hàng thành công!');
//         } catch (\Exception $e) {
//             return redirect()->back()
//                 ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Xóa tất cả đơn hàng trong thùng rác
//      */
//     public function emptyTrash()
//     {
//         try {
//             $orders = $this->orderRepo->newQuery()->onlyTrashed()->get();

//             foreach ($orders as $order) {
//                 $order->orderItems()->forceDelete();
//                 $order->payments()->forceDelete();
//                 $order->shippingAddress()->forceDelete();
//                 $order->forceDelete();
//             }

//             return redirect()->route('admin.orders.trashed')
//                 ->with('success', 'Đã xóa tất cả đơn hàng trong thùng rác!');
//         } catch (\Exception $e) {
//             return redirect()->back()
//                 ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
//         }
//     }

//     /**
//      * In hóa đơn
//      */
//     public function invoice($id)
//     {
//         $order = $this->orderRepo->newQuery()
//             ->with(['user', 'shippingAddress', 'orderItems.product', 'orderItems.variant', 'payments'])
//             ->findOrFail($id);

//         return view('admin.orders.invoice', compact('order'));
//     }

//     /**
//      * Export đơn hàng
//      */
//     public function export(Request $request)
//     {
//         // Implement export functionality (Excel, PDF, etc.)
//         // Sử dụng package như maatwebsite/excel
//     }
// }




//Bản 2: Nâng cấp khi trang thái giao hàng thì không được huỷ nữa
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private OrderRepositoryInterface $orderRepo,
        private OrderItemRepositoryInterface $orderItemRepo,
        private PaymentRepositoryInterface $paymentRepo
    ) {
    }

    /**
     * ==============================
     *  DANH SÁCH ĐƠN HÀNG
     * ==============================
     */
    public function index(Request $request)
    {
        $query = $this->orderRepo->newQuery()->with(['user', 'shippingAddress', 'payments']);

        if ($request->filled('status'))
            $query->where('status', $request->status);
        if ($request->filled('user_id'))
            $query->where('user_id', $request->user_id);
        if ($request->filled('from'))
            $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))
            $query->whereDate('created_at', '<=', $request->to);
        if ($request->filled('search'))
            $query->where('order_number', 'like', '%' . $request->search . '%');

        $orders = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => $this->orderRepo->newQuery()->count(),
            'pending' => $this->orderRepo->newQuery()->where('status', 'pending')->count(),
            'paid' => $this->orderRepo->newQuery()->where('status', 'paid')->count(),
            'shipped' => $this->orderRepo->newQuery()->where('status', 'shipped')->count(),
            'completed' => $this->orderRepo->newQuery()->where('status', 'completed')->count(),
            'cancelled' => $this->orderRepo->newQuery()->where('status', 'cancelled')->count(),
            'total_revenue' => $this->orderRepo->newQuery()
                ->where('status', 'completed')->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * ==============================
     *  CHI TIẾT ĐƠN HÀNG
     * ==============================
     */
    public function show($id)
    {
        $order = $this->orderRepo->newQuery()
            ->with(['user', 'shippingAddress', 'orderItems.product.images', 'orderItems.variant', 'payments'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * ==============================
     *  CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
     * ==============================
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,completed,cancelled',
            'admin_note' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $order = $this->orderRepo->find($id);
            $currentStatus = $order->status;

            // 🚫 Không cho hủy nếu đơn đang giao hoặc đã hoàn tất
            if (in_array($currentStatus, ['shipped', 'completed']) && $request->status === 'cancelled') {
                return redirect()->back()->with('error', 'Không thể hủy đơn hàng đang giao hoặc đã hoàn tất!');
            }

            // 🚫 Không được chuyển lùi trạng thái
            $allowedTransitions = [
                'pending' => ['paid', 'cancelled'],
                'paid' => ['shipped', 'cancelled'],
                'shipped' => ['completed'], // chỉ được hoàn tất
                'completed' => [],
                'cancelled' => [],
            ];
            if (!in_array($request->status, $allowedTransitions[$currentStatus] ?? [])) {
                return redirect()->back()->with('error', 'Trạng thái chuyển không hợp lệ!');
            }

            // Cập nhật trạng thái và thời điểm
            $data = ['status' => $request->status];
            if ($request->status === 'completed')
                $data['completed_at'] = now();
            if ($request->status === 'cancelled')
                $data['cancelled_at'] = now();
            if ($request->status === 'shipped')
                $data['shipped_at'] = now();
            if ($request->filled('admin_note'))
                $data['admin_note'] = $request->admin_note;

            $this->orderRepo->update($id, $data);

            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * ==============================
     *  HÀNH ĐỘNG NHANH
     * ==============================
     */
    public function cancel(Order $order)
    {
        if (in_array($order->status, ['shipped', 'completed', 'cancelled'])) {
            return back()->with('error', 'Không thể hủy đơn hàng đang giao, đã hoàn tất hoặc đã bị hủy.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Đơn hàng đã được hủy thành công.');
    }

    public function ship(Order $order)
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Chỉ có thể giao hàng sau khi đơn đã được thanh toán.');
        }

        $order->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        return back()->with('success', 'Đơn hàng đã chuyển sang trạng thái đang giao.');
    }

    public function complete(Order $order)
    {
        if ($order->status !== 'shipped') {
            return back()->with('error', 'Chỉ có thể hoàn tất đơn hàng đang giao.');
        }

        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Đơn hàng đã hoàn tất.');
    }

    /**
     * ==============================
     *  QUẢN LÝ THÙNG RÁC
     * ==============================
     */
    public function trashed()
    {
        $orders = $this->orderRepo->newQuery()->onlyTrashed()->with(['user'])->latest('deleted_at')->paginate(15);
        return view('admin.orders.trashed', compact('orders'));
    }

    public function restore($id)
    {
        try {
            $order = $this->orderRepo->newQuery()->onlyTrashed()->findOrFail($id);
            $order->restore();
            return redirect()->route('admin.orders.trashed')->with('success', 'Khôi phục đơn hàng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khôi phục: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $order = $this->orderRepo->newQuery()->onlyTrashed()->findOrFail($id);
            $order->orderItems()->forceDelete();
            $order->payments()->forceDelete();
            $order->shippingAddress()->forceDelete();
            $order->forceDelete();
            return redirect()->route('admin.orders.trashed')->with('success', 'Xóa vĩnh viễn đơn hàng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function emptyTrash()
    {
        try {
            $orders = $this->orderRepo->newQuery()->onlyTrashed()->get();

            foreach ($orders as $order) {
                $order->orderItems()->forceDelete();
                $order->payments()->forceDelete();
                $order->shippingAddress()->forceDelete();
                $order->forceDelete();
            }

            return redirect()->route('admin.orders.trashed')->with('success', 'Đã xóa toàn bộ đơn hàng trong thùng rác!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi xóa: ' . $e->getMessage());
        }
    }

    /**
     * ==============================
     *  HÓA ĐƠN
     * ==============================
     */
    public function invoice($id)
    {
        $order = $this->orderRepo->newQuery()
            ->with(['user', 'shippingAddress', 'orderItems.product', 'orderItems.variant', 'payments'])
            ->findOrFail($id);

        return view('admin.orders.invoice', compact('order'));
    }
}