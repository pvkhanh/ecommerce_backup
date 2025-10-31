<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class DashboardController extends Controller
{
    protected $products, $categories, $orders, $users;

    public function __construct(
        ProductRepositoryInterface $products,
        CategoryRepositoryInterface $categories,
        OrderRepositoryInterface $orders,
        UserRepositoryInterface $users
    ) {
        $this->products = $products;
        $this->categories = $categories;
        $this->orders = $orders;
        $this->users = $users;
    }

    public function index()
    {
        return view('admin.dashboard.index', [
            'productsCount' => $this->products->all()->count(),
            'categoriesCount' => $this->categories->all()->count(),
            'ordersCount' => $this->orders->all()->count(),
            'usersCount' => $this->users->all()->count(),
        ]);
    }
}
