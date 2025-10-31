<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use App\Models\Image;
use App\Models\Imageable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected function model(): string
    {
        return User::class;
    }

    // ================= AVATAR =================
    /**
     * Đặt avatar cho user, tự động bỏ avatar cũ và gán avatar mới
     */
    public function setAvatar(User $user, Image $image): Image
    {
        return DB::transaction(function () use ($user, $image) {

            // Gỡ avatar cũ
            Imageable::where('imageable_type', get_class($user))
                ->where('imageable_id', $user->id)
                ->where('is_main', true)
                ->update(['is_main' => false]);

            // Tạo mới nếu ảnh chưa tồn tại
            if (!$image->exists) {
                $image = Image::create([
                    'path' => $image->path ?? 'default-avatar.png',
                    'type' => 'avatar',
                    'alt_text' => $image->alt_text ?? 'User avatar',
                    'is_active' => true,
                ]);
            }

            // Gán avatar mới
            Imageable::updateOrCreate(
                [
                    'image_id' => $image->id,
                    'imageable_id' => $user->id,
                    'imageable_type' => get_class($user),
                ],
                [
                    'is_main' => true,
                    'position' => 1,
                ]
            );

            return $image->fresh();
        });
    }

    // ================= SEARCH & PAGINATION =================
    public function searchPaginated(?string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery();

        if ($keyword) {
            $columns = Schema::getColumnListing('users');

            $query->where(function ($q) use ($keyword, $columns) {
                foreach (['name', 'username', 'email', 'first_name', 'last_name'] as $col) {
                    if (in_array($col, $columns)) {
                        $q->orWhere($col, 'like', "%{$keyword}%");
                    }
                }
            });
        }

        return $this->paginateQuery($query, $perPage);
    }

    public function search(string $keyword): Collection
    {
        $columns = Schema::getColumnListing('users');

        return $this->newQuery()
            ->where(function ($q) use ($keyword, $columns) {
                foreach (['name', 'username', 'email', 'first_name', 'last_name'] as $col) {
                    if (in_array($col, $columns)) {
                        $q->orWhere($col, 'like', "%{$keyword}%");
                    }
                }
            })
            ->get();
    }

    // ================= FILTER =================
    public function getActive(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getByRole(string $role): Collection
    {
        return $this->model->where('role', $role)->get();
    }

    public function getByGender(string $gender): Collection
    {
        return $this->model->where('gender', $gender)->get();
    }

    public function getVerified(): Collection
    {
        return $this->model->whereNotNull('email_verified_at')->get();
    }

    public function createdBetween(string $from, string $to): Collection
    {
        return $this->model->whereBetween('created_at', [$from, $to])->get();
    }

    // ================= SOFT DELETE =================
    public function delete(int $id): bool
    {
        $model = $this->find($id);
        return $model ? (bool) $model->delete() : false;
    }

    public function forceDelete(int $id): bool
    {
        $model = $this->model->withTrashed()->find($id);
        return $model ? (bool) $model->forceDelete() : false;
    }

    public function restore(int $id): bool
    {
        $model = $this->model->onlyTrashed()->find($id);
        return $model ? (bool) $model->restore() : false;
    }

    public function findTrashed(int $id): ?User
    {
        return $this->model->onlyTrashed()->find($id);
    }
}
