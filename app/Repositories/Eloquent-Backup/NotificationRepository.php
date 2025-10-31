<?php
namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository extends BaseRepository
{
    protected function model(): string
    {
        return Notification::class;
    }

    public function getByUser(int $userId, bool $unreadOnly = false): Collection
    {
        $q = $this->newQuery()->forUser($userId)->latest();
        if ($unreadOnly)
            $q->unread();
        return $this->allQuery($q);
    }

    public function markAllAsRead(int $userId): bool
    {
        return (bool) $this->newQuery()->forUser($userId)->where('is_read', false)->getQuery()->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }
}
