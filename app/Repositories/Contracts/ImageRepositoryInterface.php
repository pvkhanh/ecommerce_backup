<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ImageRepositoryInterface extends RepositoryInterface
{
    public function primary(): Collection;
    public function gallery(): Collection;
    public function ofType(string $type): Collection;
    public function orderForDisplay(): Collection;
    public function avatar();
}
