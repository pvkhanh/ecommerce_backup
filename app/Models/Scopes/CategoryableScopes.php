<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait CategoryableScopes
{
    public function scopeByCategory(Builder $q, int $categoryId): Builder
    {
        return $q->where('category_id', $categoryId);
    }

    public function scopeOfType(Builder $q, string $morphType): Builder
    {
        return $q->where('categoryable_type', $morphType);
    }

    public function scopeOfProduct(Builder $q): Builder
    {
        return $q->ofType(\App\Models\Product::class);
    }

    public function scopeOfBlog(Builder $q): Builder
    {
        return $q->ofType(\App\Models\Blog::class);
    }
}
