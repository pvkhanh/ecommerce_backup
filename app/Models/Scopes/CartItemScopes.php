<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait CartItemScopes
{
    public function scopeByUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopeSelected(Builder $q): Builder
    {
        return $q->where('selected', true);
    }

    public function scopeForProduct(Builder $q, int $productId): Builder
    {
        return $q->where('product_id', $productId);
    }

    public function scopeForVariant(Builder $q, int $variantId): Builder
    {
        return $q->where('variant_id', $variantId);
    }
}
