<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\BlogStatus;
use App\Models\Scopes\BlogScopes;

class Blog extends Model
{
    use HasFactory, SoftDeletes, BlogScopes;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'content',
        'status'
    ];

    protected $casts = [
        'status' => BlogStatus::class,
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

       public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->orderForDisplay();
    }

    public function primaryImage()
    {
        return $this->morphOne(Image::class, 'imageable')->primary();
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
}
