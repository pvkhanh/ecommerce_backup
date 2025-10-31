<?php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait ProductReviewScopes
{
    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', \App\Enums\ReviewStatus::Approved);
    }

    public function scopeByProduct(Builder $q, int $productId): Builder
    {
        return $q->where('product_id', $productId);
    }
}
