<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use App\Models\OrderItem;


class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected function model(): string
    {
        return Order::class;
    }

    // public function getByUser(int $userId): Collection
    // {
    //     return $this->getModel()->forUser($userId)->latest()->get();
    // }

    // public function getByStatus(string $status): Collection
    // {
    //     return $this->getModel()->status($status)->latest()->get();
    // }

    // public function pending(): Collection
    // {
    //     return $this->getModel()->pending()->get();
    // }

    // public function completed(): Collection
    // {
    //     return $this->getModel()->completed()->get();
    // }

    // public function cancelled(): Collection
    // {
    //     return $this->getModel()->cancelled()->get();
    // }

    // public function dateRange(string $from, string $to): Collection
    // {
    //     return $this->getModel()->dateRange($from, $to)->get();
    // }

    // public function amountBetween(float $min, float $max): Collection
    // {
    //     return $this->getModel()->amountBetween($min, $max)->get();
    // }

    // public function getRecentOrders(int $limit = 10): Collection
    // {
    //     return $this->newQuery()->latest()->limit($limit)->get();
    // }

    // public function getRevenueByMonth(int $year): Collection
    // {
    //     return $this->newQuery()
    //         ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
    //         ->whereYear('created_at', $year)
    //         ->groupBy('month')
    //         ->get();
    // }

    /**
     * 🔹 Lấy danh sách đơn hàng của user cụ thể
     */
    public function forUser(int $userId): Collection
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    /**
     * 🔹 Tính tổng tiền đơn hàng dựa vào order_items
     */
    public function calculateTotal(int $orderId): float
    {
        $items = OrderItem::where('order_id', $orderId)->get();

        return $items->sum(fn($item) => $item->price * $item->quantity);
    }

    /**
     * 🔹 Lọc đơn hàng theo trạng thái
     */
    public function withStatus(string $status): Collection
    {
        return $this->newQuery()
            ->where('status', $status)
            ->latest()
            ->get();
    }

    /**
     * 🔹 Đánh dấu đơn hàng là đã thanh toán
     */
    public function markAsPaid(int $orderId): bool
    {
        $order = $this->find($orderId);
        if (!$order) {
            return false;
        }

        $order->update(['is_paid' => true]);
        return true;
    }

    /**
     * 🔹 Các hàm nâng cao (giữ nguyên)
     */
    public function getByUser(int $userId): Collection
    {
        return $this->newQuery()->where('user_id', $userId)->latest()->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->newQuery()->where('status', $status)->latest()->get();
    }

    public function pending(): Collection
    {
        return $this->newQuery()->where('status', 'pending')->get();
    }

    public function completed(): Collection
    {
        return $this->newQuery()->where('status', 'completed')->get();
    }

    public function cancelled(): Collection
    {
        return $this->newQuery()->where('status', 'cancelled')->get();
    }

    public function dateRange(string $from, string $to): Collection
    {
        return $this->newQuery()->whereBetween('created_at', [$from, $to])->get();
    }

    public function amountBetween(float $min, float $max): Collection
    {
        return $this->newQuery()
            ->whereBetween('total_amount', [$min, $max])
            ->get();
    }

    public function getRecentOrders(int $limit = 10): Collection
    {
        return $this->newQuery()->latest()->limit($limit)->get();
    }

    public function getRevenueByMonth(int $year): Collection
    {
        return $this->newQuery()
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->get();
    }
}