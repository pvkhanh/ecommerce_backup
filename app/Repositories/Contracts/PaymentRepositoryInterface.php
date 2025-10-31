<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface extends RepositoryInterface
{
    public function getByOrder(int $orderId): Collection;
    public function successful(): Collection;
    public function failed(): Collection;
    public function pending(): Collection;
    public function findByTransactionId(string $transactionId);
    public function updateStatus(int $id, string $status): bool;
}
