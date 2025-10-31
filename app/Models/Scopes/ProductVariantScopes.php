<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait ProductVariantScopes
{
    public function scopeByProduct(Builder $q, int $productId): Builder
    {
        return $q->where('product_id', $productId);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeSearch(Builder $q, string $keyword): Builder
    {
        return $q->where(function ($sub) use ($keyword) {
            $sub->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('sku', 'LIKE', "%{$keyword}%");
        });
    }
}
