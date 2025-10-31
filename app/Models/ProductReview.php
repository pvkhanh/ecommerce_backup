<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ReviewStatus;
use App\Models\Scopes\ProductReviewScopes;

class ProductReview extends Model
{
    use HasFactory, SoftDeletes, ProductReviewScopes;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => ReviewStatus::class,
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}