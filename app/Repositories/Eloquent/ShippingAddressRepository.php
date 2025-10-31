<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ShippingAddressRepositoryInterface;
use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Collection;

class ShippingAddressRepository extends BaseRepository implements ShippingAddressRepositoryInterface
{
    protected function model(): string
    {
        return ShippingAddress::class;
    }

    public function forUser(int $userId): Collection
    {
        return $this->getModel()->forUser($userId)->get();
    }

    public function default(): Collection
    {
        return $this->getModel()->default()->get();
    }

    public function active(): Collection
    {
        return $this->getModel()->active()->get();
    }

    public function byProvince(string $province): Collection
    {
        return $this->getModel()->byProvince($province)->get();
    }

    public function byCity(string $city): Collection
    {
        return $this->getModel()->byCity($city)->get();
    }

    public function orderForDisplay(): Collection
    {
        return $this->getModel()->orderForDisplay()->get();
    }
}
