<?php
namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository
{
    protected function model(): string
    {
        return Category::class;
    }

    public function getRootCategories(): Collection
    {
        return $this->allQuery($this->newQuery()->root()->ordered());
    }

    public function getCategoryTree(): Collection
    {
        return $this->allQuery($this->newQuery()->root()->with(['children' => fn($q) => $q->ordered()])->ordered());
    }

    public function findBySlug(string $slug)
    {
        return $this->newQuery()->with(['children', 'parent'])->where('slug', $slug)->first();
    }
}
