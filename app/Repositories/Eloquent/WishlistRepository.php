<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\WishlistRepositoryInterface;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;

class WishlistRepository extends BaseRepository implements WishlistRepositoryInterface
{
    protected function model(): string
    {
        return Wishlist::class;
    }

    public function getByUser(int $userId): Collection
    {
        return $this->getModel()->byUser($userId)->with(['product', 'variant'])->get();
    }

    public function forProduct(int $productId): Collection
    {
        return $this->getModel()->forProduct($productId)->get();
    }

    public function forVariant(int $variantId): Collection
    {
        return $this->getModel()->forVariant($variantId)->get();
    }

    public function existsEntry(int $userId, int $productId): bool
    {
        // scopeExistsEntry returns a builder with applied filters
        return $this->getModel()->existsEntry($userId, $productId)->exists();
    }

    public function addToWishlist(int $userId, int $productId, ?int $variantId = null)
    {
        return $this->getModel()->firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
            'variant_id' => $variantId,
        ]);
    }

    public function removeFromWishlist(int $userId, int $productId, ?int $variantId = null): int
    {
        $q = $this->getModel()->where('user_id', $userId)->where('product_id', $productId);
        if ($variantId !== null) {
            $q->where('variant_id', $variantId);
        }
        return $q->delete();
    }
}
