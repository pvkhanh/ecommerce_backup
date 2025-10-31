<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface MailRecipientRepositoryInterface extends RepositoryInterface
{
    public function byMail(int $mailId): Collection;
    public function byUser(int $userId): Collection;
    public function byStatus(string $status): Collection;
    public function failed(): Collection;
    public function pending(): Collection;
    public function sent(): Collection;
}
