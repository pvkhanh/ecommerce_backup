<?php
namespace App\Repositories\Eloquent;

use App\Models\ProductReview;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductReviewRepository extends BaseRepository
{
    protected function model(): string
    {
        return ProductReview::class;
    }

    public function getByProduct(int $productId, int $perPage = 10): LengthAwarePaginator
    {
        $q = $this->newQuery()->byProduct($productId)->approved()->with('user')->latest();
        return $this->paginateQuery($q, $perPage);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->allQuery($this->newQuery()->where('user_id', $userId)->with('product')->latest());
    }
}
