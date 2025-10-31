<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProductStatus;
use App\Models\Scopes\ProductScopes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes, ProductScopes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => ProductStatus::class,
    ];

    /* ===================== RELATIONSHIPS ===================== */

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    /**
     * Ảnh liên kết với sản phẩm (qua bảng imageables)
     */
    // public function images()
    // {
    //     return $this->belongsToMany(Image::class, 'imageables', 'imageable_id', 'image_id')
    //         ->wherePivot('imageable_type', self::class)
    //         ->withPivot('is_main', 'position')
    //         ->withTimestamps()
    //         ->orderByPivot('position');
    // }


    /**
     * Ảnh liên kết với sản phẩm (qua bảng imageables)
     */
    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable') // chỉ cần 'imageable'
            ->withPivot('is_main', 'position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    /**
     * Tồn kho qua variants
     */
    public function stockItems()
    {
        return $this->hasManyThrough(
            StockItem::class,
            ProductVariant::class,
            'product_id',
            'variant_id',
            'id',
            'id'
        );
    }

    /* ===================== ACCESSORS ===================== */

    /**
     * Ảnh chính (is_main = true)
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()
            ->wherePivot('is_main', true)
            ->first();
    }

    /**
     * URL ảnh chính
     */
    public function getMainImageUrlAttribute(): string
    {
        $primaryImage = $this->primary_image;

        if ($primaryImage) {
            return $primaryImage->url ?? asset('images/no-image.png');
        }

        $firstImage = $this->images()->first();
        return $firstImage?->url ?? asset('images/no-image.png');
    }

    /**
     * Giá thấp nhất trong các biến thể
     */
    public function getMinPriceAttribute()
    {
        return $this->variants->min('price') ?? $this->price;
    }

    /**
     * Giá cao nhất trong các biến thể
     */
    public function getMaxPriceAttribute()
    {
        return $this->variants->max('price') ?? $this->price;
    }

    /**
     * Khoảng giá hiển thị
     */
    public function getPriceRangeAttribute(): string
    {
        if ($this->variants->isEmpty()) {
            return number_format($this->price, 0, ',', '.') . 'đ';
        }

        $min = $this->min_price;
        $max = $this->max_price;

        if ($min == $max) {
            return number_format($min, 0, ',', '.') . 'đ';
        }

        return number_format($min, 0, ',', '.') . 'đ - ' . number_format($max, 0, ',', '.') . 'đ';
    }

    /**
     * Tổng tồn kho của tất cả biến thể
     */
    public function getTotalStockAttribute(): int
    {
        if ($this->variants->isEmpty()) {
            return 0;
        }

        return $this->variants->sum(function ($variant) {
            return $variant->stockItems->sum('quantity');
        });
    }

    /**
     * Điểm trung bình đánh giá (1–5)
     */
    public function getAverageRatingAttribute()
    {
        return round($this->reviews->avg('rating') ?? 0, 1);
    }

    /**
     * Tổng số đánh giá
     */
    public function getReviewCountAttribute(): int
    {
        return $this->reviews->count();
    }

    /**
     * Tên danh mục đầu tiên
     */
    public function getFirstCategoryNameAttribute(): string
    {
        return $this->categories->first()?->name ?? 'Chưa phân loại';
    }

    /**
     * Tất cả tên danh mục (string)
     */
    public function getCategoryNamesAttribute(): string
    {
        return $this->categories->pluck('name')->join(', ');
    }

    /**
     * Kiểm tra còn hàng
     */
    public function getInStockAttribute(): bool
    {
        return $this->total_stock > 0;
    }

    /**
     * Kiểm tra tồn kho thấp (dưới 10)
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock > 0 && $this->total_stock <= 10;
    }

    /**
     * Badge màu cho stock
     */
    public function getStockBadgeColorAttribute(): string
    {
        $stock = $this->total_stock;

        if ($stock > 50)
            return 'success';
        if ($stock > 10)
            return 'warning';
        if ($stock > 0)
            return 'danger';
        return 'dark';
    }

    /**
     * Label trạng thái từ enum
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * Màu badge trạng thái từ enum
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    /* ===================== HELPER METHODS ===================== */

    /**
     * Kiểm tra sản phẩm có thể bán không
     */
    public function isSaleable(): bool
    {
        return $this->status === ProductStatus::Active && $this->in_stock;
    }

    /**
     * Kiểm tra sản phẩm hiển thị trên website không
     */
    public function isVisible(): bool
    {
        return $this->status->isVisible();
    }

    /**
     * Kiểm tra có thể chỉnh sửa không
     */
    public function isEditable(): bool
    {
        return $this->status->isEditable();
    }

    /**
     * Kiểm tra có thể chuyển sang trạng thái khác không
     */
    public function canTransitionTo(ProductStatus $status): bool
    {
        return $this->status->canTransitionTo($status);
    }

    /**
     * Cập nhật trạng thái tự động dựa trên tồn kho
     */
    public function updateStockStatus(): void
    {
        $totalStock = $this->total_stock;

        // Nếu hết hàng và đang Active -> chuyển sang OutOfStock
        if ($totalStock <= 0 && $this->status === ProductStatus::Active) {
            $this->update(['status' => ProductStatus::OutOfStock->value]);
        }

        // Nếu có hàng và đang OutOfStock -> chuyển về Active
        elseif ($totalStock > 0 && $this->status === ProductStatus::OutOfStock) {
            $this->update(['status' => ProductStatus::Active->value]);
        }
    }

    /**
     * Kiểm tra có thể thêm vào giỏ hàng không
     */
    public function canAddToCart(int $quantity = 1, ?int $variantId = null): bool
    {
        if (!$this->isSaleable()) {
            return false;
        }

        // Nếu có variant ID
        if ($variantId) {
            $variant = $this->variants()->find($variantId);
            if (!$variant) {
                return false;
            }

            $variantStock = $variant->stockItems->sum('quantity');
            return $variantStock >= $quantity;
        }

        // Nếu không có variant, check tổng stock
        return $this->total_stock >= $quantity;
    }

    /**
     * Giảm tồn kho khi đặt hàng
     */
    public function decreaseStock(int $quantity, ?int $variantId = null): bool
    {
        if ($variantId) {
            $variant = $this->variants()->find($variantId);
            if (!$variant) {
                return false;
            }

            // Lấy stock item có đủ số lượng
            $stockItem = $variant->stockItems()
                ->where('quantity', '>=', $quantity)
                ->orderBy('quantity', 'desc')
                ->first();

            if ($stockItem) {
                $stockItem->decrement('quantity', $quantity);
                $this->updateStockStatus();
                return true;
            }
        } else {
            // Giảm từ variant đầu tiên có stock
            foreach ($this->variants as $variant) {
                $stockItem = $variant->stockItems()
                    ->where('quantity', '>=', $quantity)
                    ->first();

                if ($stockItem) {
                    $stockItem->decrement('quantity', $quantity);
                    $this->updateStockStatus();
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Tăng tồn kho khi hủy đơn/hoàn hàng
     */
    public function increaseStock(int $quantity, ?int $variantId = null): bool
    {
        if ($variantId) {
            $variant = $this->variants()->find($variantId);
            if (!$variant) {
                return false;
            }

            $stockItem = $variant->stockItems()->first();
            if ($stockItem) {
                $stockItem->increment('quantity', $quantity);
                $this->updateStockStatus();
                return true;
            }
        } else {
            // Tăng vào variant đầu tiên
            $firstVariant = $this->variants()->first();
            if ($firstVariant) {
                $stockItem = $firstVariant->stockItems()->first();
                if ($stockItem) {
                    $stockItem->increment('quantity', $quantity);
                    $this->updateStockStatus();
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Kiểm tra user đã đánh giá chưa
     */
    public function hasReviewedBy(int $userId): bool
    {
        return $this->reviews()->where('user_id', $userId)->exists();
    }

    /**
     * Kiểm tra user đã wishlist chưa
     */
    public function isWishlistedBy(int $userId): bool
    {
        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    /**
     * Scope - Sản phẩm liên quan (cùng danh mục)
     */
    public function getRelatedProducts(int $limit = 4)
    {
        $categoryIds = $this->categories->pluck('id');

        return self::whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
            ->where('id', '!=', $this->id)
            ->active()
            ->limit($limit)
            ->get();
    }

    /* ===================== STATIC HELPERS ===================== */

    /**
     * Tìm sản phẩm theo slug
     */
    public static function findBySlug(string $slug)
    {
        return self::where('slug', $slug)->firstOrFail();
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = self::generateUniqueSlug($product->name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug) && !empty($product->name)) {
                $product->slug = self::generateUniqueSlug($product->name);
            }
        });
    }

    /**
     * Sinh slug duy nhất
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $count = self::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
