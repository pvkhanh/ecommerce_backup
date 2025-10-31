<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\ShippingAddressScopes;

class ShippingAddress extends Model
{
    use HasFactory, ShippingAddressScopes;

    protected $fillable = [
        'order_id',
        'receiver_name',
        'phone',
        'address',
        'province',
        'district',
        'ward',
        'postal_code',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}