<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait WishlistScopes
{
    public function scopeByUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopeForProduct(Builder $q, int $productId): Builder
    {
        return $q->where('product_id', $productId);
    }

    public function scopeForVariant(Builder $q, int $variantId): Builder
    {
        return $q->where('variant_id', $variantId);
    }

    public function scopeExistsEntry(Builder $q, int $userId, int $productId, ?int $variantId = null): Builder
    {
        return $q->byUser($userId)
                 ->forProduct($productId)
                 ->when($variantId, fn($qq) => $qq->forVariant($variantId));
    }
}
