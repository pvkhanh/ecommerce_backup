<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait OrderScopes
{
    public function scopeStatus(Builder $q, string $status): Builder
    {
        return $q->where('status', $status);
    }

    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', 'pending');
    }

    public function scopeCompleted(Builder $q): Builder
    {
        return $q->where('status', 'completed');
    }

    public function scopeCancelled(Builder $q): Builder
    {
        return $q->where('status', 'cancelled');
    }

    public function scopeDateRange(Builder $q, ?string $from, ?string $to, string $column = 'created_at'): Builder
    {
        return $q->when($from, fn($qq) => $qq->whereDate($column, '>=', $from))
                 ->when($to,   fn($qq) => $qq->whereDate($column, '<=', $to));
    }

    public function scopeAmountBetween(Builder $q, ?float $min, ?float $max, string $column = 'total_amount'): Builder
    {
        return $q
            ->when($min !== null, fn($qq) => $qq->where($column, '>=', $min))
            ->when($max !== null, fn($qq) => $qq->where($column, '<=', $max));
    }
}
