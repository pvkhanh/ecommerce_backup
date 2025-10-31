<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected function model(): string
    {
        return Product::class;
    }

    /**
     * Tạo sản phẩm mới kèm sync categories và images
     */
    public function create(array $data): Product
    {
        // Lấy image_ids & primary_image_id
        $imageIds = $data['image_ids'] ?? [];
        $primaryImageId = $data['primary_image_id'] ?? ($imageIds[0] ?? null);

        // Tạo sản phẩm
        $product = $this->model->create($data);

        // Sync categories
        if (!empty($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        // Sync images
        if (!empty($imageIds)) {
            $syncData = [];
            foreach ($imageIds as $position => $imageId) {
                $syncData[$imageId] = [
                    'is_main' => $imageId == $primaryImageId,
                    'position' => $position + 1
                ];
            }
            $product->images()->sync($syncData);
        }

        return $product;
    }

    /**
     * Cập nhật sản phẩm kèm sync categories và images
     */
    public function updateAndReturn(int $id, array $data): Product
    {
        $product = $this->model->findOrFail($id);

        $imageIds = $data['image_ids'] ?? [];
        $primaryImageId = $data['primary_image_id'] ?? ($imageIds[0] ?? null);

        // Update dữ liệu cơ bản
        $product->update($data);

        // Sync categories
        if (isset($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        // Sync images
        if (!empty($imageIds)) {
            $syncData = [];
            foreach ($imageIds as $position => $imageId) {
                $syncData[$imageId] = [
                    'is_main' => $imageId == $primaryImageId,
                    'position' => $position + 1
                ];
            }
            $product->images()->sync($syncData);
        } else {
            $product->images()->detach();
        }

        return $product->fresh(); // Load lại quan hệ
    }

    /**
     * Tìm sản phẩm
     */
    public function find(int $id): Product
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Xóa sản phẩm
     */
    public function delete(int $id): bool
    {
        $product = $this->find($id);
        return $product->delete();
    }


    public function bulkDelete(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function bulkUpdateStatus(array $ids, string $status): int
    {
        return $this->model->whereIn('id', $ids)->update(['status' => $status]);
    }

    public function getActive(): Collection
    {
        return $this->where('status', ProductStatus::Active->value)->get();
    }

    public function search(string $keyword): Collection
    {
        return $this->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%");
        })->get();
    }

    public function priceBetween(float $min, float $max): Collection
    {
        return $this->whereBetween('price', [$min, $max])->get();
    }

    public function byCategory(int $categoryId): Collection
    {
        return Product::whereHas('categories', fn($q) => $q->where('categories.id', $categoryId))->get();
    }

    public function hasVariants(): Collection
    {
        return Product::has('variants')->get();
    }

    // public function searchPaginated(?string $keyword, int $perPage = 15): LengthAwarePaginator
    // {
    //     $query = $this->newQuery();
    //     if ($keyword) {
    //         $query->where('name', 'like', "%$keyword%")
    //             ->orWhere('description', 'like', "%$keyword%");
    //     }
    //     // 🔹 Sắp xếp mặc định: sản phẩm mới/cập nhật gần nhất lên đầu
    //     $query->orderBy('updated_at', 'desc');

    //     return $query->paginate($perPage);
    // }
    public function searchPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {

        $query = $this->newQuery();

        // Tìm theo keyword
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%")
                    ->orWhereHas('variants', function ($q2) use ($keyword) {
                        $q2->where('sku', 'like', "%$keyword%");
                    });
            });
        }

        // Lọc danh mục
        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', fn($q) => $q->where('categories.id', $filters['category_id']));
        }

        // Lọc trạng thái
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Lọc khoảng giá
        if (!empty($filters['price_range'])) {
            [$min, $max] = explode('-', str_replace(' ', '', $filters['price_range']));
            $query->whereBetween('price', [(float) $min, (float) $max]);
        }

        // Sắp xếp
        switch ($filters['sort_by'] ?? 'latest') {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            // case 'sales':
            //     $query->orderBy('sales_count', 'desc'); // giả sử bạn có cột sales_count
            //     break;
            default:
                $query->orderBy('updated_at', 'desc');
        }

        return $query->paginate($perPage)->withQueryString();
    }



    public function findBySlug(string $slug): ?Product
    {
        return $this->firstBy('slug', $slug);
    }

    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->count();
    }

    public function countOutOfStock(): int
    {
        return Product::with('variants.stockItems')->get()->filter(function ($product) {
            return $product->total_stock <= 0;
        })->count();
    }

    public function getOutOfStock(): Collection
    {
        return Product::with('variants.stockItems')->get()->filter(function ($product) {
            return $product->total_stock <= 0;
        });
    }

    // Restore tất cả sản phẩm đã xóa
    public function restoreAll(): int
    {
        return $this->getModel()->onlyTrashed()->restore();
    }

    // Xóa vĩnh viễn tất cả sản phẩm đã xóa
    public function forceDeleteAll(): int
    {
        return $this->getModel()->onlyTrashed()->forceDelete();
    }

    // Nếu chưa có phương thức restore riêng cho 1 sản phẩm
    public function restore(int $id): bool
    {
        $product = $this->getModel()->onlyTrashed()->find($id);
        return $product ? $product->restore() : false;
    }
    public function forceDelete(int $id): bool
    {
        $product = $this->getModel()->onlyTrashed()->find($id);
        return $product ? $product->forceDelete() : false;
    }

}
