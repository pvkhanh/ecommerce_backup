<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    protected function model(): string
    {
        return Notification::class;
    }

    public function getByUser(int $userId): Collection
    {
        return $this->getModel()->byUser($userId)->get();
    }

    public function unread(): Collection
    {
        return $this->getModel()->unread()->get();
    }

    public function read(): Collection
    {
        return $this->getModel()->read()->get();
    }

    public function ofType(string $type): Collection
    {
        return $this->getModel()->ofType($type)->get();
    }

    public function expired(): Collection
    {
        return $this->getModel()->expired()->get();
    }

    public function notExpired(): Collection
    {
        return $this->getModel()->notExpired()->get();
    }

    public function getUnreadByUser(int $userId): Collection
    {
        return $this->getModel()->byUser($userId)->unread()->notExpired()->get();
    }

    public function markAsRead(int $id): bool
    {
        $n = $this->find($id);
        if (! $n) return false;
        return (bool) $n->update(['is_read' => true, 'read_at' => now()]);
    }

    public function clearOldNotifications(int $days = 30): int
    {
        return $this->getModel()->where('created_at', '<', now()->subDays($days))->delete();
    }
}
