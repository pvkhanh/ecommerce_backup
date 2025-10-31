<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait StockItemScopes
{
    public function scopeForVariant(Builder $q, int $variantId): Builder
    {
        return $q->where('variant_id', $variantId);
    }

    public function scopeForProduct(Builder $q, int $productId): Builder
    {
        return $q->where('product_id', $productId);
    }

    public function scopeInStock(Builder $q): Builder
    {
        return $q->where('quantity', '>', 0);
    }

    public function scopeLowStock(Builder $q, int $threshold = 5): Builder
    {
        return $q->whereBetween('quantity', [1, $threshold]);
    }

    public function scopeOutOfStock(Builder $q): Builder
    {
        return $q->where('quantity', '=', 0);
    }
}
