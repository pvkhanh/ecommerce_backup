<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CategoryableRepositoryInterface;
use App\Models\Categoryable;
use Illuminate\Database\Eloquent\Collection;

class CategoryableRepository extends BaseRepository implements CategoryableRepositoryInterface
{
    protected function model(): string
    {
        return Categoryable::class;
    }

    public function ofType(string $type): Collection
    {
        return $this->getModel()->ofType($type)->get();
    }

    public function ofProduct(int $productId): Collection
    {
        return $this->getModel()->ofProduct()->where('categoryable_id', $productId)->get();
    }

    public function ofBlog(int $blogId): Collection
    {
        return $this->getModel()->ofBlog()->where('categoryable_id', $blogId)->get();
    }
}
