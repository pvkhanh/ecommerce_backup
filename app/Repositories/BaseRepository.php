<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Closure;

/**
 * Class BaseRepository
 *
 * Repository gốc cung cấp các phương thức thao tác Eloquent Model chuẩn:
 * - CRUD cơ bản
 * - Query linh hoạt (Builder)
 * - Transaction wrapper
 * - Có thể mở rộng bởi các Repository con
 */
abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * Khởi tạo model tự động.
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Xác định model chính được Repository sử dụng.
     */
    abstract protected function model(): string;

    /**
     * Khởi tạo instance của model từ container.
     */
    protected function makeModel(): void
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new RuntimeException("{$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        $this->model = $model;
    }

    /**
     * Tạo query builder mới.
     */
    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Lấy tất cả record.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->newQuery()->get($columns);
    }

    /**
     * Tìm theo ID.
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Tìm theo ID, lỗi nếu không thấy.
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Lấy bản ghi đầu tiên theo cột.
     */
    public function firstBy(string $column, mixed $value): ?Model
    {
        return $this->newQuery()->where($column, $value)->first();
    }

    /**
     * Kiểm tra sự tồn tại của bản ghi.
     */
    public function existsBy(string $column, mixed $value): bool
    {
        return $this->newQuery()->where($column, $value)->exists();
    }

    /**
     * Tạo bản ghi mới.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Cập nhật bản ghi theo ID.
     */
    public function update(int $id, array $data): bool
    {
        $model = $this->find($id);
        return $model ? $model->update($data) : false;
    }

    // /**
    //  * Xóa mềm bản ghi.
    //  */
    // public function delete(int $id): bool
    // {
    //     $model = $this->find($id);
    //     return $model ? (bool) $model->delete() : false;
    // }

    // /**
    //  * Xóa vĩnh viễn bản ghi.
    //  */
    // public function forceDelete(int $id): bool
    // {
    //     $model = $this->find($id);
    //     return $model ? (bool) $model->forceDelete() : false;
    // }

    // =================== XÓA MỀM ===================
    public function delete(int $id): bool
    {
        $model = $this->find($id);
        return $model ? (bool) $model->delete() : false;
    }

    // =================== XÓA VĨNH VIỄN ===================
    public function forceDelete(int $id): bool
    {
        // withTrashed để tìm cả bản ghi đã soft delete
        $model = $this->model->withTrashed()->find($id);
        return $model ? (bool) $model->forceDelete() : false;
    }

    // =================== KHÔI PHỤC ===================
    public function restore(int $id): bool
    {
        $model = $this->model->onlyTrashed()->find($id);
        return $model ? (bool) $model->restore() : false;
    }

    // =================== TÌM TRASHED ===================
    public function findTrashed(int $id): ?Model
    {
        return $this->model->onlyTrashed()->find($id);
    }


    /**
     * Phân trang query.
     */
    public function paginateQuery(Builder $query, int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $query->paginate($perPage, $columns);
    }

    /**
     * Lấy tất cả từ query builder (không phân trang).
     */
    public function allQuery(Builder $query, array $columns = ['*']): Collection
    {
        return $query->get($columns);
    }

    /**
     * Chạy callback trong transaction.
     */
    public function transaction(Closure $callback): mixed
    {
        return DB::transaction($callback);
    }

    /**
     * Lấy model hiện tại.
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Khởi tạo builder với eager load.
     */
    public function with(array $relations): Builder
    {
        return $this->newQuery()->with($relations);
    }

    /**
     * Tìm theo điều kiện tùy chỉnh.
     */
    public function where(string|array $column, $operator = null, $value = null): Builder
    {
        $query = $this->newQuery();
        is_array($column)
            ? $query->where($column)
            : $query->where($column, $operator, $value);

        return $query;
    }

    /**
     * Đếm số lượng bản ghi.
     */
    public function count(array $conditions = []): int
    {
        return $this->newQuery()->where($conditions)->count();
    }

    /**
     * Lấy bản ghi đầu tiên (có điều kiện tuỳ chọn).
     */
    public function first(array $conditions = []): ?Model
    {
        $query = $this->newQuery();
        if (!empty($conditions)) {
            $query->where($conditions);
        }
        return $query->first();
    }
}
