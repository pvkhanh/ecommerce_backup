<?php

namespace App\Models\Traits;

use App\Models\Image;
use App\Models\Imageable;

trait HasImages
{
    public function imageables()
    {
        return $this->morphMany(Imageable::class, 'imageable');
    }

    public function images()
    {
        return $this->morphMany(Imageable::class, 'imageable')
            ->with('image')
            ->orderByDesc('is_main')
            ->orderBy('position');
    }

    public function mainImage()
    {
        return $this->morphOne(Imageable::class, 'imageable')
            ->where('is_main', true)
            ->with('image');
    }

    public function addImage(Image $image, bool $isMain = false, int $position = 0)
    {
        return $this->imageables()->create([
            'image_id' => $image->id,
            'is_main' => $isMain,
            'position' => $position,
        ]);
    }
}
