<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\CategoryableScopes;

class Categoryable extends Model
{
    use HasFactory, CategoryableScopes;
// Disable timestamps to avoid errors when tables don't have created_at/updated_at    public $timestamps = false;
    protected $fillable = [
        'category_id',
        'categoryable_id',
        'categoryable_type',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function categoryable()
    {
        return $this->morphTo();
    }
}
