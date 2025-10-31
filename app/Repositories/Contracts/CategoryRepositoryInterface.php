<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getTree(): Collection;
    public function search(string $keyword): Collection;
    public function getRootCategories(): Collection;
    public function getChildren(int $parentId): Collection;
}
