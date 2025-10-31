<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\StockItemRepositoryInterface;
use App\Models\StockItem;
use Illuminate\Database\Eloquent\Collection;

class StockItemRepository extends BaseRepository implements StockItemRepositoryInterface
{
    protected function model(): string
    {
        return StockItem::class;
    }

    public function forVariant(int $variantId): Collection
    {
        return $this->getModel()->forVariant($variantId)->get();
    }

    public function forProduct(int $productId): Collection
    {
        return $this->getModel()->forProduct($productId)->get();
    }

    public function inStock(): Collection
    {
        return $this->getModel()->inStock()->get();
    }

    public function lowStock(int $threshold = 5): Collection
    {
        return $this->getModel()->lowStock($threshold)->get();
    }

    public function outOfStock(): Collection
    {
        return $this->getModel()->outOfStock()->get();
    }

    public function updateOrCreateStock(int $variantId, string $location, int $quantity)
    {
        return $this->getModel()->updateOrCreate(
            ['variant_id' => $variantId, 'location' => $location],
            ['quantity' => $quantity]
        );
    }

    public function increaseStock(int $variantId, int $quantity, string $location = 'default'): bool
    {
        $stock = $this->getModel()->firstOrCreate(
            ['variant_id' => $variantId, 'location' => $location],
            ['quantity' => 0]
        );

        return $stock->update(['quantity' => $stock->quantity + $quantity]);
    }

    public function decreaseStock(int $variantId, int $quantity, ?string $location = null): bool
    {
        $stock = $location
            ? $this->getModel()->where('variant_id', $variantId)->where('location', $location)->first()
            : $this->getModel()->where('variant_id', $variantId)->orderBy('quantity', 'desc')->first();

        if (! $stock || $stock->quantity < $quantity) {
            return false;
        }

        return $stock->update(['quantity' => $stock->quantity - $quantity]);
    }
}
