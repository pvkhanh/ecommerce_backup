<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface StockItemRepositoryInterface extends RepositoryInterface
{
    public function forVariant(int $variantId): Collection;
    public function forProduct(int $productId): Collection;
    public function inStock(): Collection;
    public function lowStock(int $threshold = 5): Collection;
    public function outOfStock(): Collection;
    public function updateOrCreateStock(int $variantId, string $location, int $quantity);
    public function increaseStock(int $variantId, int $quantity, string $location = 'default'): bool;
    public function decreaseStock(int $variantId, int $quantity, ?string $location = null): bool;
}
