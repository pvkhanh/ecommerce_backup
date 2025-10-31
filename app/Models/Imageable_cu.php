<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imageable extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_id',
        'imageable_id',
        'imageable_type',
        'is_main',
        'position',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    /**
     * Liên kết tới bảng images
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * Quan hệ ngược tới model sở hữu (user, product,...)
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
