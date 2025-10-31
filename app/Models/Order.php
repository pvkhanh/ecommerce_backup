<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\OrderStatus;
use App\Models\Scopes\OrderScopes;

class Order extends Model
{
    use HasFactory, SoftDeletes, OrderScopes;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'shipping_fee',
        'customer_note',
        'admin_note',
        'status',
        'delivered_at',
        'completed_at',
        'cancelled_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'status' => OrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
