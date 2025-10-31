<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait ImageableScopes
{
    public function scopeForModel(Builder $q, string $modelType, int $modelId): Builder
    {
        return $q->where('imageable_type', $modelType)
            ->where('imageable_id', $modelId);
    }

    public function scopeMain(Builder $q): Builder
    {
        return $q->where('is_main', true);
    }

    public function scopeGallery(Builder $q): Builder
    {
        return $q->where('is_main', false);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('position')->orderByDesc('id');
    }

    public function scopeOfImageType(Builder $q, string $type): Builder
    {
        return $q->whereHas('image', fn($q2) => $q2->where('type', $type));
    }
}
