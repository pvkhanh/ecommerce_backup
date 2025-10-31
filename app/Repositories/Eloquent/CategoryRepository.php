<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected function model(): string
    {
        return Category::class;
    }

    public function getTree(): Collection
    {
        return $this->getModel()->tree()->get();
    }

    public function search(string $keyword): Collection
    {
        return $this->getModel()->search($keyword)->get();
    }

    public function getRootCategories(): Collection
    {
        return $this->getModel()->whereNull('parent_id')->get();
    }

    public function getChildren(int $parentId): Collection
    {
        return $this->getModel()->where('parent_id', $parentId)->get();
    }
}
