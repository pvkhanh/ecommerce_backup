<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ImageableRepositoryInterface;
use App\Models\Imageable;
use Illuminate\Database\Eloquent\Collection;

class ImageableRepository extends BaseRepository implements ImageableRepositoryInterface
{
    protected function model(): string
    {
        return Imageable::class;
    }

    public function forModel(string $modelType, int $modelId): Collection
    {
        return $this->getModel()->forModel($modelType, $modelId)->get();
    }

    public function primary(): Collection
    {
        return $this->getModel()->primary()->get();
    }

    public function gallery(): Collection
    {
        return $this->getModel()->gallery()->get();
    }
}
