<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\MailRecipientRepositoryInterface;
use App\Models\MailRecipient;
use Illuminate\Database\Eloquent\Collection;

class MailRecipientRepository extends BaseRepository implements MailRecipientRepositoryInterface
{
    protected function model(): string
    {
        return MailRecipient::class;
    }

    public function byMail(int $mailId): Collection
    {
        return $this->getModel()->byMail($mailId)->get();
    }

    public function byUser(int $userId): Collection
    {
        return $this->getModel()->byUser($userId)->get();
    }

    public function byStatus(string $status): Collection
    {
        return $this->getModel()->byStatus($status)->get();
    }

    public function failed(): Collection
    {
        return $this->getModel()->failed()->get();
    }

    public function pending(): Collection
    {
        return $this->getModel()->pending()->get();
    }

    public function sent(): Collection
    {
        return $this->getModel()->sent()->get();
    }
}
