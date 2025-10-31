<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\CategoryScopes;

class Category extends Model
{
    use HasFactory, SoftDeletes, CategoryScopes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'level',
        'position',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function categoryables()
    {
        return $this->hasMany(Categoryable::class);
    }
    public function products()
    {
        return $this->morphedByMany(Product::class, 'categoryable');
    }
}