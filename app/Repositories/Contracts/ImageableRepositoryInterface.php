<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ImageableRepositoryInterface extends RepositoryInterface
{
    public function forModel(string $modelType, int $modelId): Collection;
    public function primary(): Collection;
    public function gallery(): Collection;
}
