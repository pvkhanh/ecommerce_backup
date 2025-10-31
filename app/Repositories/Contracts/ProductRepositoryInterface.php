<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getActive(): Collection;

    public function search(string $keyword): Collection;

    public function priceBetween(float $min, float $max): Collection;

    public function byCategory(int $categoryId): Collection;

    public function hasVariants(): Collection;

    // public function searchPaginated(?string $keyword, int $perPage = 15): LengthAwarePaginator;
    public function searchPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;


    public function findBySlug(string $slug);
}
