<?php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait UserScopes
{
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeRole(Builder $q, string $role): Builder
    {
        return $q->where('role', $role);
    }

    public function scopeGender(Builder $q, string $gender): Builder
    {
        return $q->where('gender', $gender);
    }

    public function scopeSearch(Builder $q, string $keyword): Builder
    {
        return $q->where(function ($sub) use ($keyword) {
            $sub->where('username', 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%")
                ->orWhere('first_name', 'LIKE', "%{$keyword}%")
                ->orWhere('last_name', 'LIKE', "%{$keyword}%");
        });
    }

    public function scopeVerified(Builder $q): Builder
    {
        return $q->whereNotNull('email_verified_at');
    }

    public function scopeCreatedBetween(Builder $q, ?string $from, ?string $to): Builder
    {
        return $q->when($from, fn($qq) => $qq->whereDate('created_at', '>=', $from))
            ->when($to, fn($qq) => $qq->whereDate('created_at', '<=', $to));
    }
}
