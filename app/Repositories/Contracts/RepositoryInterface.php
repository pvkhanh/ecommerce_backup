<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface RepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    public function find(int $id): ?Model;
    public function findOrFail(int $id): Model;
    public function create(array $data): Model;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function forceDelete(int $id): bool;
    public function paginateQuery(Builder $query, int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;
    public function allQuery(Builder $query, array $columns = ['*']): Collection;
}
