<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderController extends Controller
{
    public function __construct(protected OrderRepositoryInterface $orderRepository)
    {
    }

    public function index(Request $request)
    {
        $keyword = $request->query('search', null);

        if (method_exists($this->orderRepository, 'searchPaginated')) {
            $orders = $this->orderRepository->searchPaginated($keyword, 10);
        } else {
            $query = $this->orderRepository->newQuery();
            if ($keyword) {
                $query->where('order_number', 'like', "%{$keyword}%");
            }
            $orders = $this->orderRepository->paginateQuery($query, 10);
        }

        return view('admin.orders.index', compact('orders', 'keyword'));
    }

    public function show($id)
    {
        $order = $this->orderRepository->findOrFail((int) $id);

        return view('admin.orders.show', compact('order'));
    }

    public function destroy($id)
    {
        $this->orderRepository->delete((int) $id);

        return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công!');
    }
    public function create()
    {
        $users = User::all();
        return view('admin.orders.create', compact('users'));
    }

    public function edit(Order $order)
    {
        $users = User::all();
        return view('admin.orders.edit', compact('order', 'users'));
    }

}
