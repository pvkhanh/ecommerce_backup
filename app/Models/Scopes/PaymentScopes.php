<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use App\Enums\PaymentStatus;

trait PaymentScopes
{
    public function scopeByOrder(Builder $q, int $orderId): Builder
    {
        return $q->where('order_id', $orderId);
    }

    public function scopeSuccessful(Builder $q): Builder
    {
        return $q->where('status', PaymentStatus::Success);
    }

    public function scopeFailed(Builder $q): Builder
    {
        return $q->where('status', PaymentStatus::Failed);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', PaymentStatus::Pending);
    }
}
