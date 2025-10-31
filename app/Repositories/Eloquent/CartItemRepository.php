<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CartItemRepositoryInterface;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CartItemRepository extends BaseRepository implements CartItemRepositoryInterface
{
    /**
     * Xác định model tương ứng với repository này.
     */
    protected function model(): string
    {
        return CartItem::class;
    }

    /**
     * Lấy danh sách sản phẩm trong giỏ hàng của user.
     */
    public function getByUser(int $userId): Collection
    {
        return $this->model
            ->byUser($userId)
            ->with(['product', 'variant'])
            ->get();
    }

    /**
     * Thêm hoặc cập nhật sản phẩm trong giỏ hàng.
     */
    public function addOrUpdate(
        int $userId,
        int $productId,
        int $quantity,
        ?int $variantId = null
    ): Model {
        return $this->transaction(function () use ($userId, $productId, $quantity, $variantId) {
            $query = $this->model
                ->where('user_id', $userId)
                ->where('product_id', $productId);

            if ($variantId !== null) {
                $query->where('variant_id', $variantId);
            } else {
                $query->whereNull('variant_id');
            }

            $existing = $query->first();

            if ($existing) {
                $existing->increment('quantity', $quantity);
                return $existing->refresh();
            }

            return $this->create([
                'user_id' => $userId,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'selected' => true,
            ]);
        });
    }

    /**
     * Xóa toàn bộ giỏ hàng của user.
     */
    public function clearUserCart(int $userId): int
    {
        return $this->model->where('user_id', $userId)->delete();
    }

    /**
     * Lấy danh sách sản phẩm được chọn trong giỏ hàng.
     */
    public function selectedForUser(int $userId): Collection
    {
        return $this->model
            ->byUser($userId)
            ->selected()
            ->with(['product', 'variant'])
            ->get();
    }
}
