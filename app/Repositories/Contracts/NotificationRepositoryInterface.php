<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface NotificationRepositoryInterface extends RepositoryInterface
{
    public function getByUser(int $userId): Collection;
    public function unread(): Collection;
    public function read(): Collection;
    public function ofType(string $type): Collection;
    public function expired(): Collection;
    public function notExpired(): Collection;
    public function getUnreadByUser(int $userId): Collection;
    public function markAsRead(int $id): bool;
    public function clearOldNotifications(int $days = 30): int;
}
