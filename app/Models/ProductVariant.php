<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\ProductVariantScopes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes, ProductVariantScopes;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function stockItems()
    {
        return $this->hasMany(StockItem::class, 'variant_id');
    }
    public function cartItems()
    {
        // return $this->hasMany(CartItem::class);
        return $this->hasMany(CartItem::class, 'variant_id');

    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function orders()
    {
        // return $this->belongsToMany(Order::class, 'order_items');
        return $this->belongsToMany(Order::class, 'order_items', 'variant_id', 'order_id')
            ->withTimestamps();
    }
}
