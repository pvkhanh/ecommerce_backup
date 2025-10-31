<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\MailRepositoryInterface;
use App\Models\Mail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MailRepository extends BaseRepository implements MailRepositoryInterface
{
    /**
     * Xác định model tương ứng với repository này
     */
    protected function model(): string
    {
        return Mail::class;
    }

    /**
     * Lấy mail theo khóa định danh (key)
     */
    public function byKey(string $key): ?Mail
    {
        return $this->model->byKey($key)->first();
    }

    /**
     * Lấy danh sách mail theo loại (type)
     */
    public function ofType(string $type): Collection
    {
        return $this->model->ofType($type)->get();
    }

    /**
     * Tìm kiếm mail theo từ khóa
     */
    public function search(string $keyword): Collection
    {
        return $this->model->search($keyword)->get();
    }

    /**
     * Lấy danh sách mail mới gửi gần đây
     */
    public function latestSent(): Collection
    {
        return $this->model->latestSent()->get();
    }

    /**
     * Lấy danh sách mail có phân trang và bộ lọc
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->newQuery();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['template_key'])) {
            $query->where('template_key', $filters['template_key']);
        }

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        return $this->paginateQuery($query->orderByDesc('created_at'), $perPage);
    }
}
