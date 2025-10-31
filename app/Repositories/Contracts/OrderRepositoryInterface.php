<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function getByUser(int $userId): Collection;
    public function getByStatus(string $status): Collection;
    public function pending(): Collection;
    public function completed(): Collection;
    public function cancelled(): Collection;
    public function dateRange(string $from, string $to): Collection;
    public function amountBetween(float $min, float $max): Collection;
    public function getRecentOrders(int $limit = 10): Collection;
    public function getRevenueByMonth(int $year): Collection;
}
