<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ShippingAddressRepositoryInterface extends RepositoryInterface
{
    public function forUser(int $userId): Collection;
    public function default(): Collection;
    public function active(): Collection;
    public function byProvince(string $province): Collection;
    public function byCity(string $city): Collection;
    public function orderForDisplay(): Collection;
}
