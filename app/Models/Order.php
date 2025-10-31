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
        'paid_at',
        'shipped_at',
        'delivered_at',
        'completed_at',
        'cancelled_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'status' => OrderStatus::class,
    ];

    // Relationships
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

    // Computed Attributes
    public function getSubtotalAttribute(): float
    {
        return (float) $this->orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getTotalAmountAttribute($value): float
    {
        // Nếu đã có giá trị trong DB, ưu tiên dùng giá trị đó
        if ($value > 0) {
            return (float) $value;
        }

        // Nếu chưa có, tính động từ items + shipping
        return $this->subtotal + ($this->shipping_fee ?? 0);
    }

    // Tính toán và cập nhật total_amount
    public function calculateAndUpdateTotal(): void
    {
        $subtotal = $this->subtotal;
        $shippingFee = $this->shipping_fee ?? 0;
        $total = $subtotal + $shippingFee;

        $this->update(['total_amount' => $total]);
    }

    // Events
    protected static function booted()
    {
        // Tự động tính total khi lưu order
        static::saving(function ($order) {
            if ($order->isDirty('shipping_fee')) {
                $subtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
                $order->total_amount = $subtotal + ($order->shipping_fee ?? 0);
            }
        });

        // Tự động cập nhật timestamp khi thay đổi status
        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                match ($order->status) {
                    OrderStatus::Paid => $order->paid_at = $order->paid_at ?? now(),
                    OrderStatus::Shipped => $order->shipped_at = $order->shipped_at ?? now(),
                    OrderStatus::Completed => $order->completed_at = $order->completed_at ?? now(),
                    OrderStatus::Cancelled => $order->cancelled_at = $order->cancelled_at ?? now(),
                    default => null,
                };
            }
        });
    }
}

// <!-- namespace App\Models;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Enums\OrderStatus;
// use App\Models\Scopes\OrderScopes;

// class Order extends Model
// {
// use HasFactory, SoftDeletes, OrderScopes;

// protected $fillable = [
// 'user_id',
// 'order_number',
// 'total_amount',
// 'shipping_fee',
// 'customer_note',
// 'admin_note',
// 'status',
// 'delivered_at',
// 'completed_at',
// 'cancelled_at'
// ];

// protected $casts = [
// 'total_amount' => 'decimal:2',
// 'shipping_fee' => 'decimal:2',
// 'delivered_at' => 'datetime',
// 'completed_at' => 'datetime',
// 'cancelled_at' => 'datetime',
// 'status' => OrderStatus::class,
// ];

// public function user()
// {
// return $this->belongsTo(User::class);
// }

// public function shippingAddress()
// {
// return $this->hasOne(ShippingAddress::class);
// }

// public function orderItems()
// {
// return $this->hasMany(OrderItem::class);
// }

// public function payments()
// {
// return $this->hasMany(Payment::class);
// }
// public function getSubtotalAttribute(): float
// {
// return $this->orderItems->sum(function ($item) {
// return $item->price * $item->quantity;
// });
// }
// public function getTotalAmountAttribute($value): float
// {
// // Nếu DB đã có giá trị -> ưu tiên hiển thị
// if ($value > 0) {
// return (float) $value;
// }

// // Nếu chưa có, tính động theo item + shipping_fee
// return (float) ($this->subtotal + $this->shipping_fee);
// }
// protected static function booted()
// {
// static::saving(function ($order) {
// $subtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
// $order->total_amount = $subtotal + $order->shipping_fee;
// });
// }

// } -->