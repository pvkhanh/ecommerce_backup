<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface OrderItemRepositoryInterface extends RepositoryInterface
{
    public function forOrder(int $orderId): Collection;
    public function forProduct(int $productId): Collection;
    public function createMany(array $items);
}
