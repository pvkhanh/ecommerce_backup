<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait MailRecipientScopes
{
    public function scopeByMail(Builder $query, int $mailId): Builder
    {
        return $query->where('mail_id', $mailId);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', 'sent');
    }
}
