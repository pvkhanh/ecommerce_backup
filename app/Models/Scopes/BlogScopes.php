<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use App\Enums\BlogStatus;

trait BlogScopes
{
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', BlogStatus::Published);
    }

    public function scopeDraft(Builder $q): Builder
    {
        return $q->where('status', BlogStatus::Draft);
    }

    public function scopeByAuthor(Builder $q, int $authorId): Builder
    {
        return $q->where('author_id', $authorId);
    }

    public function scopeSearch(Builder $q, string $keyword): Builder
    {
        return $q->where(function ($sub) use ($keyword) {
            $sub->where('title', 'LIKE', "%{$keyword}%")
                ->orWhere('slug', 'LIKE', "%{$keyword}%")
                ->orWhere('content', 'LIKE', "%{$keyword}%");
        });
    }
}
