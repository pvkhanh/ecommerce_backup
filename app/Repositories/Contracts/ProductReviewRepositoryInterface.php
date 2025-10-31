<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProductReviewRepositoryInterface extends RepositoryInterface
{
    public function getApprovedByProduct(int $productId): Collection;
    public function getPendingReviews(): Collection;
    public function getByUser(int $userId): Collection;
}
