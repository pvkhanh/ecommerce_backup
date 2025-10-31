<?php
namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends BaseRepository
{
    protected function model(): string
    {
        return Payment::class;
    }

    public function getByOrder(int $orderId): Collection
    {
        return $this->allQuery($this->newQuery()->byOrder($orderId));
    }

    public function findByTransactionId(string $transactionId)
    {
        return $this->newQuery()->where('transaction_id', $transactionId)->first();
    }
}
