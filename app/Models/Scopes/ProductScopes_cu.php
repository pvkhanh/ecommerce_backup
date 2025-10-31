<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use App\Enums\ProductStatus;

trait ProductScopes
{
    /**
     * Lọc sản phẩm đang hoạt động
     */
    public function scopeActive(Builder $q): Builder
    {
        // ⚠️ Quan trọng: Khi dùng Enum, phải so sánh theo ->value
        return $q->where('status', ProductStatus::Active->value);
    }

    /**
     * Tìm kiếm sản phẩm theo tên, mô tả, hoặc slug
     */
    public function scopeSearch(Builder $q, string $keyword): Builder
    {
        return $q->where(function (Builder $sub) use ($keyword) {
            $sub->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('description', 'LIKE', "%{$keyword}%")
                ->orWhere('slug', 'LIKE', "%{$keyword}%");
        });
    }

    /**
     * Lọc theo khoảng giá (min / max có thể null)
     */
    public function scopePriceBetween(Builder $q, ?float $min, ?float $max): Builder
    {
        return $q
            ->when(!is_null($min), fn($qq) => $qq->where('price', '>=', $min))
            ->when(!is_null($max), fn($qq) => $qq->where('price', '<=', $max));
    }

    /**
     * Lọc sản phẩm thuộc danh mục cụ thể
     */
    public function scopeCategoryId(Builder $q, int $categoryId): Builder
    {
        return $q->whereHas('categories', function (Builder $qq) use ($categoryId) {
            $qq->where('categories.id', $categoryId);
        });
    }

    /**
     * Lọc sản phẩm có ít nhất 1 biến thể
     */
    public function scopeHasVariants(Builder $q): Builder
    {
        return $q->whereHas('variants');
    }
}
