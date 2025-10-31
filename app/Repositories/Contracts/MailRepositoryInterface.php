<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Mail;

interface MailRepositoryInterface extends BaseRepositoryInterface
{
    public function byKey(string $key): ?Mail;
    public function ofType(string $type): Collection;
    public function search(string $keyword): Collection;
    public function latestSent(): Collection;
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator;
}
