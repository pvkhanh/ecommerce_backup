<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use App\Models\Scopes\PaymentScopes;

class Payment extends Model
{
    use HasFactory, PaymentScopes;

    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'amount',
        'paid_at',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_method' => PaymentMethod::class,
        'status' => PaymentStatus::class,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}