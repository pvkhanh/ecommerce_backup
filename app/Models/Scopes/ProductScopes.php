<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use App\Enums\ProductStatus;

trait ProductScopes
{
    /**
     * Lọc sản phẩm đang hoạt động
     */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', ProductStatus::Active->value);
    }

    /**
     * Lọc sản phẩm theo trạng thái
     */
    public function scopeStatus(Builder $q, ProductStatus|string $status): Builder
    {
        $value = $status instanceof ProductStatus ? $status->value : $status;
        return $q->where('status', $value);
    }

    /**
     * Lọc sản phẩm có thể bán (dựa trên stock_items)
     */
    public function scopeSaleable(Builder $q): Builder
    {
        return $q->where('status', ProductStatus::Active->value)
            ->whereHas('stockItems', function ($query) {
                $query->havingRaw('SUM(quantity) > 0');
            });
    }

    /**
     * Lọc sản phẩm hiển thị trên website
     */
    public function scopeVisible(Builder $q): Builder
    {
        return $q->whereIn('status', [
            ProductStatus::Active->value,
            ProductStatus::OutOfStock->value
        ]);
    }

    /**
     * Lọc sản phẩm hết hàng (dựa trên stock_items)
     */
    public function scopeOutOfStock(Builder $q): Builder
    {
        return $q->where('status', ProductStatus::OutOfStock->value)
            ->orWhereDoesntHave('stockItems')
            ->orWhereHas('stockItems', function ($query) {
                $query->havingRaw('SUM(quantity) <= 0');
            });
    }

    /**
     * Lọc sản phẩm tồn kho thấp (dựa trên stock_items)
     */
    public function scopeLowStock(Builder $q, int $threshold = 10): Builder
    {
        return $q->whereHas('stockItems', function ($query) use ($threshold) {
            $query->selectRaw('SUM(quantity) as total_stock')
                ->havingRaw('SUM(quantity) > 0')
                ->havingRaw('SUM(quantity) <= ?', [$threshold]);
        });
    }

    /**
     * Lọc sản phẩm có tồn kho
     */
    public function scopeInStock(Builder $q): Builder
    {
        return $q->whereHas('stockItems', function ($query) {
            $query->havingRaw('SUM(quantity) > 0');
        });
    }

    /**
     * Tìm kiếm sản phẩm theo tên, mô tả, hoặc slug
     */
    public function scopeSearch($query, $keyword)
    {
        $query->where(function ($q) use ($keyword) {
            $q->where('products.name', 'LIKE', "%{$keyword}%")
                ->orWhere('products.description', 'LIKE', "%{$keyword}%")
                ->orWhere('products.slug', 'LIKE', "%{$keyword}%")
                // Tìm SKU trong bảng product_variants
                ->orWhereHas('variants', function ($variantQuery) use ($keyword) {
                    $variantQuery->where('sku', 'LIKE', "%{$keyword}%");
                });
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
     * Lọc theo khoảng giá từ chuỗi "min-max"
     */
    public function scopePriceRange(Builder $q, ?string $range): Builder
    {
        if (empty($range)) {
            return $q;
        }

        $parts = explode('-', $range);
        $min = isset($parts[0]) && is_numeric($parts[0]) ? (float) $parts[0] : null;
        $max = isset($parts[1]) && is_numeric($parts[1]) ? (float) $parts[1] : null;

        return $q->priceBetween($min, $max);
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
     * Lọc sản phẩm thuộc nhiều danh mục
     */
    public function scopeCategoryIds(Builder $q, array $categoryIds): Builder
    {
        return $q->whereHas('categories', function (Builder $qq) use ($categoryIds) {
            $qq->whereIn('categories.id', $categoryIds);
        });
    }

    /**
     * Lọc sản phẩm có ít nhất 1 biến thể
     */
    public function scopeHasVariants(Builder $q): Builder
    {
        return $q->whereHas('variants');
    }

    /**
     * Lọc sản phẩm không có biến thể
     */
    public function scopeWithoutVariants(Builder $q): Builder
    {
        return $q->whereDoesntHave('variants');
    }

    /**
     * Sắp xếp theo giá
     */
    public function scopeSortByPrice(Builder $q, string $direction = 'asc'): Builder
    {
        return $q->orderBy('price', $direction);
    }

    /**
     * Sắp xếp theo số lượng đã bán
     */
    public function scopeSortBySales(Builder $q, string $direction = 'desc'): Builder
    {
        return $q->orderBy('sales_count', $direction);
    }

    /**
     * Sắp xếp theo đánh giá
     */
    public function scopeSortByRating(Builder $q, string $direction = 'desc'): Builder
    {
        return $q->orderBy('rating', $direction);
    }

    /**
     * Sắp xếp theo ngày tạo
     */
    public function scopeNewest(Builder $q): Builder
    {
        return $q->orderBy('created_at', 'desc');
    }

    /**
     * Sắp xếp theo ngày cập nhật
     */
    public function scopeLatest(Builder $q): Builder
    {
        return $q->orderBy('updated_at', 'desc');
    }

    /**
     * Lọc sản phẩm được tạo trong khoảng thời gian
     */
    public function scopeCreatedBetween(Builder $q, $startDate, $endDate): Builder
    {
        return $q->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Lọc sản phẩm có giảm giá
     */
    public function scopeOnSale(Builder $q): Builder
    {
        return $q->where('sale_price', '>', 0)
            ->whereColumn('sale_price', '<', 'price');
    }

    /**
     * Lọc sản phẩm nổi bật
     */
    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    /**
     * Lọc sản phẩm mới
     */
    public function scopeNew(Builder $q, int $days = 30): Builder
    {
        return $q->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Lọc sản phẩm bán chạy
     */
    public function scopeBestSelling(Builder $q, int $limit = 10): Builder
    {
        return $q->orderBy('sales_count', 'desc')->limit($limit);
    }
}