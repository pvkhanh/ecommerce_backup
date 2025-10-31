<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait UserAddressScopes
{
    public function scopeByUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopeDefault(Builder $q): Builder
    {
        return $q->where('is_default', true);
    }
}
