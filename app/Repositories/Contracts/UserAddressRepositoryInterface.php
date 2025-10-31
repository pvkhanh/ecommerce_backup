<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface UserAddressRepositoryInterface extends RepositoryInterface
{
    public function getByUser(int $userId): Collection;
    public function getDefaultForUser(int $userId);
}
