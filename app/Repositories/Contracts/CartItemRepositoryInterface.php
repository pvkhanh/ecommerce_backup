<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CartItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUser(int $userId): Collection;
    public function addOrUpdate(int $userId, int $productId, int $quantity, ?int $variantId = null): Model;
    public function clearUserCart(int $userId): int;
    public function selectedForUser(int $userId): Collection;
}
