<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\UserAddressRepositoryInterface;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Collection;

class UserAddressRepository extends BaseRepository implements UserAddressRepositoryInterface
{
    /**
     * Xác định model tương ứng với repository này
     */
    protected function model(): string
    {
        return UserAddress::class;
    }

    /**
     * Lấy danh sách địa chỉ của một user
     */
    public function getByUser(int $userId): Collection
    {
        return $this->model->forUser($userId)->get();
    }

    /**
     * Lấy địa chỉ mặc định của user
     */
    public function getDefaultForUser(int $userId): ?UserAddress
    {
        return $this->model->forUser($userId)->default()->first();
    }
}
