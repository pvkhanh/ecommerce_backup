<?php
namespace App\Repositories\Eloquent;

use App\Models\CartItem;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository extends BaseRepository
{
    protected function model(): string
    {
        return CartItem::class;
    }

    public function getByUser(int $userId): Collection
    {
        return $this->allQuery($this->newQuery()->byUser($userId)->with(['product', 'variant']));
    }

    public function findCartItem(int $userId, int $productId, ?int $variantId = null)
    {
        $q = $this->newQuery()->byUser($userId)->where('product_id', $productId);
        $q->when($variantId !== null, fn($qq) => $qq->where('variant_id', $variantId));
        return $q->first();
    }

    public function clearUserCart(int $userId): bool
    {
        return (bool) $this->newQuery()->byUser($userId)->getQuery()->delete();
    }
}
