<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\CartItemScopes;

class CartItem extends Model
{
    use HasFactory, SoftDeletes, CartItemScopes;

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'quantity',
        'selected',
    ];

    protected $casts = [
        'selected' => 'boolean',
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}