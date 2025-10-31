<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

trait CategoryScopes
{
    public function scopeActive(Builder $q): Builder
    {
        // return $q->where('is_active', true);
        return $q; // 🟢 Bỏ lọc trạng thái
    }

    public function scopeParent(Builder $q): Builder
    {
        return $q->whereNull('parent_id');
    }

    public function scopeChildrenOf(Builder $q, int $parentId): Builder
    {
        return $q->where('parent_id', $parentId);
    }

    public function scopeSearch(Builder $q, string $keyword): Builder
    {
        return $q->where(function ($sub) use ($keyword) {
            $sub->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('slug', 'LIKE', "%{$keyword}%");
        });
    }

    /** Load category cây 2 cấp (dùng menu / sidebar) */
    public function scopeTree(Builder $q): Builder
    {
        return $q->with(['children' => fn($child) => $child->active()->orderBy('position')])
            ->parent()
            ->active()
            ->orderBy('position');
    }

    /**
     * Filter categories that are assigned to a specific model type
     * Example: Category::ofType(Product::class)->get();
     */
    public function scopeOfType(Builder $q, string $morphClass): Builder
    {
        return $q->whereHas('categoryables', function ($sub) use ($morphClass) {
            $sub->where('categoryable_type', $morphClass);
        });
    }

    /**
     * Shortcut for Product-specific categories
     * ->ofProduct()
     */
    public function scopeOfProduct(Builder $q): Builder
    {
        return $q->ofType(\App\Models\Product::class);
    }

    /**
     * Shortcut for Blog/Article if needed (safe to leave here)
     * ->ofBlog()
     */
    public function scopeOfBlog(Builder $q): Builder
    {
        return $q->ofType(\App\Models\Blog::class);
    }
}
