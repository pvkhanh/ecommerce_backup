<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    protected function model(): string
    {
        return Image::class;
    }

    public function primary(): Collection
    {
        return $this->getModel()->primary()->get();
    }

    public function gallery(): Collection
    {
        return $this->getModel()->gallery()->get();
    }

    public function ofType(string $type): Collection
    {
        return $this->getModel()->ofType($type)->get();
    }

    public function orderForDisplay(): Collection
    {
        return $this->getModel()->orderForDisplay()->get();
    }

    public function avatar()
    {
        return $this->getModel()->avatar()->first();
    }
}
