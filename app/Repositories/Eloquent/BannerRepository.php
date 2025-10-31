<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\BannerRepositoryInterface;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    protected function model(): string
    {
        return Banner::class;
    }

    public function getActive(): Collection
    {
        return $this->getModel()->active()->orderForDisplay()->get();
    }

    public function scheduled()
    {
        return $this->getModel()->scheduled()->get();
    }

    public function visible()
    {
        return $this->getModel()->visible()->get();
    }

    public function ofType(string $type): Collection
    {
        return $this->getModel()->ofType($type)->get();
    }

    public function updatePositions(array $positions): bool
    {
        foreach ($positions as $id => $pos) {
            $this->update($id, ['position' => $pos]);
        }
        return true;
    }
}
