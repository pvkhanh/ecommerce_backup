<?php
namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository extends BaseRepository
{
    protected function model(): string
    {
        return Order::class;
    }

    public function paginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = $this->newQuery()->with(['user', 'orderItems.product', 'shippingAddress', 'payments']);

        $q->when(!empty($filters['search']), fn($qq) => $qq->where('order_number', 'LIKE', "%{$filters['search']}%"));
        $q->when(!empty($filters['status']), fn($qq) => $qq->status($filters['status']));
        $q->when(!empty($filters['user_id']), fn($qq) => $qq->where('user_id', $filters['user_id']));

        if (!empty($filters['date_from']) || !empty($filters['date_to'])) {
            $q->dateRange($filters['date_from'] ?? null, $filters['date_to'] ?? null);
        }

        $sort = $filters['sort'] ?? 'created_at';
        $dir = $filters['direction'] ?? 'desc';

        return $this->paginateQuery($q->orderBy($sort, $dir), $perPage);
    }

    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return $this->newQuery()->where('order_number', $orderNumber)->first();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $data = ['status' => $status];

        if ($status === 'delivered')
            $data['delivered_at'] = now();
        if ($status === 'completed')
            $data['completed_at'] = now();
        if ($status === 'cancelled')
            $data['cancelled_at'] = now();

        return $this->update($id, $data);
    }
}
