<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Closure;

interface BaseRepositoryInterface
{
    /**
     * Lấy toàn bộ bản ghi
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Tìm bản ghi theo ID
     */
    public function find(int $id): ?Model;

    /**
     * Tìm bản ghi hoặc fail nếu không có
     */
    public function findOrFail(int $id): Model;

    /**
     * Tìm bản ghi đầu tiên theo cột
     */
    public function firstBy(string $column, $value): ?Model;

    /**
     * Kiểm tra sự tồn tại của bản ghi
     */
    public function existsBy(string $column, $value): bool;

    /**
     * Tạo bản ghi mới
     */
    public function create(array $data): Model;

    /**
     * Cập nhật bản ghi
     */
    public function update(int $id, array $data): bool;

    /**
     * Xóa bản ghi
     */
    public function delete(int $id): bool;

    /**
     * Xóa vĩnh viễn bản ghi
     */
    public function forceDelete(int $id): bool;

    /**
     * Phân trang query
     */
    public function paginateQuery(Builder $query, int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Lấy tất cả dữ liệu theo query builder
     */
    public function allQuery(Builder $query, array $columns = ['*']): Collection;

    /**
     * Thực thi giao dịch database (transaction)
     */
    public function transaction(Closure $callback);
}
