<?php
namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository
{
    protected function model(): string
    {
        return Product::class;
    }

    public function paginatedWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $q = $this->newQuery()->with(['images', 'categories', 'variants']);

        $q->when(!empty($filters['search']), fn($qq) => $qq->search($filters['search']));
        $q->when(!empty($filters['status']), fn($qq) => $qq->where('status', $filters['status']));
        $q->when(isset($filters['min_price']) || isset($filters['max_price']), fn($qq) => $qq->priceBetween($filters['min_price'] ?? null, $filters['max_price'] ?? null));
        $q->when(!empty($filters['category_id']), fn($qq) => $qq->categoryId($filters['category_id']));
        $q->when(!empty($filters['has_variants']), fn($qq) => $qq->has('variants'));

        $sort = $filters['sort'] ?? 'created_at';
        $dir = $filters['direction'] ?? 'desc';

        return $this->paginateQuery($q->orderBy($sort, $dir), $perPage);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->newQuery()->with(['images', 'categories', 'variants', 'reviews'])->where('slug', $slug)->first();
    }

    public function getActive(): Collection
    {
        return $this->allQuery($this->newQuery()->active());
    }
}
