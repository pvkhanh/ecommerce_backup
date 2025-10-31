<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Enums\NotificationType;

trait NotificationScopes
{
    public function scopeByUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopeUnread(Builder $q): Builder
    {
        return $q->where('is_read', false);
    }

    public function scopeRead(Builder $q): Builder
    {
        return $q->where('is_read', true);
    }

    public function scopeOfType(Builder $q, NotificationType|string $type): Builder
    {
        // Cho phép truyền enum hoặc string
        $value = $type instanceof NotificationType ? $type->value : $type;
        return $q->where('type', $value);
    }

    public function scopeExpired(Builder $q): Builder
    {
        return $q->whereNotNull('expires_at')
                 ->where('expires_at', '<', Carbon::now());
    }

    public function scopeNotExpired(Builder $q): Builder
    {
        return $q->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', Carbon::now());
        });
    }
}
