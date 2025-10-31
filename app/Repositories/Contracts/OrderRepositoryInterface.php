<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy đơn hàng theo user
     */
    public function forUser(int $userId): Collection;

    /**
     * Lấy đơn hàng theo status
     */
    public function withStatus(string $status): Collection;

    /**
     * Đánh dấu đã thanh toán
     */
    public function markAsPaid(int $orderId): bool;

    /**
     * Tính tổng tiền đơn hàng
     */
    public function calculateTotal(int $orderId): float;

    /**
     * Lấy đơn hàng pending
     */
    public function pending(): Collection;

    /**
     * Lấy đơn hàng completed
     */
    public function completed(): Collection;

    /**
     * Lấy đơn hàng cancelled
     */
    public function cancelled(): Collection;

    /**
     * Lọc theo khoảng ngày
     */
    public function dateRange(string $from, string $to): Collection;

    /**
     * Lọc theo khoảng tiền
     */
    public function amountBetween(float $min, float $max): Collection;

    /**
     * Đơn hàng gần đây
     */
    public function getRecentOrders(int $limit = 10): Collection;

    /**
     * Doanh thu theo tháng
     */
    public function getRevenueByMonth(int $year): Collection;
}



// <!-- namespace App\Repositories\Contracts;
// use Illuminate\Database\Eloquent\Collection;

// interface OrderRepositoryInterface extends RepositoryInterface
// {
// public function getByUser(int $userId): Collection;
// public function getByStatus(string $status): Collection;
// public function pending(): Collection;
// public function completed(): Collection;
// public function cancelled(): Collection;
// public function dateRange(string $from, string $to): Collection;
// public function amountBetween(float $min, float $max): Collection;
// public function getRecentOrders(int $limit = 10): Collection;
// public function getRevenueByMonth(int $year): Collection;
// } -->