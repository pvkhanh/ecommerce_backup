<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait MailScopes
{
    public function scopeByKey(Builder $query, string $templateKey): Builder
    {
        return $query->where('template_key', $templateKey);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('subject', 'like', "%{$keyword}%")
              ->orWhere('content', 'like', "%{$keyword}%")
              ->orWhere('template_key', 'like', "%{$keyword}%");
        });
    }

    public function scopeLatestSent(Builder $query): Builder
    {
        return $query->latest('created_at');
    }
}
