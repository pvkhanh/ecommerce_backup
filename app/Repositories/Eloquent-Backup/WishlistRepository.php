<?php
namespace App\Repositories\Eloquent;

use App\Models\Wishlist;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class WishlistRepository extends BaseRepository
{
    protected function model(): string
    {
        return Wishlist::class;
    }

    public function getByUser(int $userId): Collection
    {
        return $this->allQuery($this->newQuery()->where('user_id', $userId)->with(['product', 'variant'])->latest());
    }

    public function exists(int $userId, int $productId, ?int $variantId = null): bool
    {
        $q = $this->newQuery()->where('user_id', $userId)->where('product_id', $productId);
        $q->when($variantId !== null, fn($qq) => $qq->where('variant_id', $variantId));
        return (bool) $q->exists();
    }
}
