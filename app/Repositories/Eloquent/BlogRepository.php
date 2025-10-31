<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{
    protected function model(): string
    {
        return Blog::class;
    }

    public function getPublished(): Collection
    {
        return $this->getModel()->published()->latest()->get();
    }

    public function search(string $keyword): Collection
    {
        return $this->getModel()->search($keyword)->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->getModel()->where('category_id', $categoryId)->published()->latest()->get();
    }

    public function getByAuthor(int $authorId): Collection
    {
        return $this->getModel()->byAuthor($authorId)->published()->latest()->get();
    }
}
