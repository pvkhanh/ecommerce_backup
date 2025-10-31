<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface WishlistRepositoryInterface extends RepositoryInterface
{
    public function getByUser(int $userId): Collection;
    public function forProduct(int $productId): Collection;
    public function forVariant(int $variantId): Collection;
    public function existsEntry(int $userId, int $productId): bool;
    public function addToWishlist(int $userId, int $productId, ?int $variantId = null);
    public function removeFromWishlist(int $userId, int $productId, ?int $variantId = null): int;
}
