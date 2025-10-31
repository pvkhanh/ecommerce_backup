<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    protected function model(): string
    {
        return Payment::class;
    }

    public function getByOrder(int $orderId): Collection
    {
        return $this->getModel()->byOrder($orderId)->get();
    }

    public function successful(): Collection
    {
        return $this->getModel()->successful()->get();
    }

    public function failed(): Collection
    {
        return $this->getModel()->failed()->latest()->get();
    }

    public function pending(): Collection
    {
        return $this->getModel()->pending()->get();
    }

    public function findByTransactionId(string $transactionId)
    {
        return $this->newQuery()->where('transaction_id', $transactionId)->first();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $data = ['status' => $status];
        if ($status === 'success') {
            $data['paid_at'] = now();
        }
        return $this->update($id, $data);
    }
}
