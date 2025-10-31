<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait ImageScopes
{
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }

    // public function scopeOrderForDisplay(Builder $q): Builder
    // {
    //     return $q->orderByDesc('id');
    // }
    // 🔹 Thêm scope này để test không bị lỗi
    public function scopeOrderForDisplay($query)
    {
        return $query->orderByDesc('is_main')->orderBy('created_at');
    }
}
