<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\StockItemScopes;

class StockItem extends Model
{
    use HasFactory, SoftDeletes, StockItemScopes;

    protected $fillable = [
        'variant_id',
        'quantity',
        'location',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    //Thêm ngày 27/10/2025
    /**
     * Relationship to ProductVariant
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Relationship to Product (through variant)
     */
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            ProductVariant::class,
            'id',           // Foreign key on ProductVariant table
            'id',           // Foreign key on Product table
            'variant_id',   // Local key on StockItem table
            'product_id'    // Local key on ProductVariant table
        );
    }

    /**
     * Check if stock is available
     */
    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * Check if stock is low
     */
    public function isLow(int $threshold = 5): bool
    {
        return $this->quantity > 0 && $this->quantity <= $threshold;
    }

    /**
     * Check if stock is out
     */
    public function isOut(): bool
    {
        return $this->quantity <= 0;
    }
}