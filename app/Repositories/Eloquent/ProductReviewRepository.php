<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ProductReviewRepositoryInterface;
use App\Models\ProductReview;
use Illuminate\Database\Eloquent\Collection;

class ProductReviewRepository extends BaseRepository implements ProductReviewRepositoryInterface
{
    protected function model(): string
    {
        return ProductReview::class;
    }

    public function getApprovedByProduct(int $productId): Collection
    {
        return $this->getModel()->byProduct($productId)->approved()->latest()->get();
    }

    public function getPendingReviews(): Collection
    {
        // Use scope if exists, otherwise fallback
        return $this->getModel()->where('status', 'pending')->latest()->get();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->getModel()->where('user_id', $userId)->latest()->get();
    }
}
