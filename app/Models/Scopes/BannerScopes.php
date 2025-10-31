<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

trait BannerScopes
{
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeScheduled(Builder $q): Builder
    {
        $now = Carbon::now();

        return $q->where(function ($query) use ($now) {
            $query
                // start_at NULL → hiển thị ngay
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_at')
                      ->orWhere('start_at', '<=', $now);
                })
                // end_at NULL → hiển thị vô thời hạn
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_at')
                      ->orWhere('end_at', '>=', $now);
                });
        });
    }

    public function scopeVisible(Builder $q): Builder
    {
        return $q->active()->scheduled();
    }

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }

    public function scopeOrderForDisplay(Builder $q): Builder
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }
}
