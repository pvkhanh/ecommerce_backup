<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait ShippingAddressScopes
{
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByProvince(Builder $query, string $province): Builder
    {
        return $query->where('province', $province);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopeOrderForDisplay(Builder $query): Builder
    {
        return $query->orderByDesc('is_default')
            ->orderBy('id');
    }
}
