<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function setAvatar(User $user, Image $image): Image;
    public function getActive(): Collection;
    public function getByRole(string $role): Collection;
    public function getByGender(string $gender): Collection;
    public function search(string $keyword): Collection;
    public function getVerified(): Collection;
    public function createdBetween(string $from, string $to): Collection;
}
