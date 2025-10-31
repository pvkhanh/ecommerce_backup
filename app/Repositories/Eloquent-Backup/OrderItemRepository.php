<?php
namespace App\Repositories\Eloquent;

use App\Models\OrderItem;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderItemRepository extends BaseRepository
{
    protected function model(): string
    {
        return OrderItem::class;
    }

    public function getByOrder(int $orderId): Collection
    {
        return $this->allQuery($this->newQuery()->where('order_id', $orderId)->with(['product', 'variant']));
    }
}
