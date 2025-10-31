<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CategoryableRepositoryInterface extends RepositoryInterface
{
    public function ofType(string $type): Collection;
    public function ofProduct(int $productId): Collection;
    public function ofBlog(int $blogId): Collection;
}
