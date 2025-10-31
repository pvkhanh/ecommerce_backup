<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\UserAddressScopes;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes, UserAddressScopes;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',
        'address',
        'province',
        'district',
        'ward',
        'postal_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
