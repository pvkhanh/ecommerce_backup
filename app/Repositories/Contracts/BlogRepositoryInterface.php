<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface BlogRepositoryInterface extends RepositoryInterface
{
    public function getPublished(): Collection;
    public function search(string $keyword): Collection;
    public function getByCategory(int $categoryId): Collection;
    public function getByAuthor(int $authorId): Collection;
}
