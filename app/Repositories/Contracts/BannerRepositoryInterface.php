<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface BannerRepositoryInterface extends RepositoryInterface
{
    public function getActive(): Collection;
    public function scheduled();
    public function visible();
    public function ofType(string $type): Collection;
    public function updatePositions(array $positions): bool;
}
