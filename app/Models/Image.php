<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'type',
        'alt_text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Quan hệ đa hình: Ảnh có thể thuộc nhiều model khác nhau
     * Ví dụ: User, Product, Category,...
     */
    public function imageables()
    {
        return $this->hasMany(Imageable::class);
    }

    /**
     * Lấy model sở hữu ảnh (dùng khi eager load)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'imageables', 'image_id', 'imageable_id')
            ->wherePivot('imageable_type', User::class)
            ->withPivot('is_main', 'position')
            ->withTimestamps();
    }

    // Thêm các quan hệ tương tự cho Product, Category nếu cần
    //23/10
    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'imageables', 'image_id', 'imageable_id')
    //         ->wherePivot('imageable_type', Product::class)
    //         ->withPivot('is_main', 'position')
    //         ->withTimestamps();
    // }
    public function products()
    {
        return $this->morphedByMany(Product::class, 'imageable')
            ->withPivot('is_main', 'position')
            ->withTimestamps();
    }

}