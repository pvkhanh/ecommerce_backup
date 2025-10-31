<?php
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository
{
    protected function model(): string
    {
        return User::class;
    }

    public function paginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = $this->newQuery()->with(['addresses', 'orders']);

        $q->when(!empty($filters['search']), fn($qq) => $qq->search($filters['search']));
        $q->when(!empty($filters['role']), fn($qq) => $qq->role($filters['role']));
        $q->when(isset($filters['is_active']), fn($qq) => $filters['is_active'] ? $qq->active() : $qq);
        $q->when(!empty($filters['gender']), fn($qq) => $qq->gender($filters['gender']));

        if (!empty($filters['created_from']) || !empty($filters['created_to'])) {
            $q->createdBetween($filters['created_from'] ?? null, $filters['created_to'] ?? null);
        }

        $sort = $filters['sort'] ?? 'created_at';
        $dir = $filters['direction'] ?? 'desc';

        return $this->paginateQuery($q->orderBy($sort, $dir), $perPage);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->newQuery()->where('email', $email)->first();
    }

    public function getActiveUsers(): Collection
    {
        return $this->allQuery($this->newQuery()->active());
    }
}
