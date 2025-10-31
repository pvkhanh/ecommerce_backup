<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    protected function model(): string
    {
        return OrderItem::class;
    }

    public function forOrder(int $orderId): Collection
    {
        return $this->getModel()->where('order_id', $orderId)->with(['product', 'variant'])->get();
    }

    public function forProduct(int $productId): Collection
    {
        return $this->getModel()->where('product_id', $productId)->get();
    }

    public function createMany(array $items)
    {
        return $this->getModel()->insert($items);
    }
}
