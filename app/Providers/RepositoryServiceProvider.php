<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Danh sách binding giữa Interface và Repository
     *
     * @var array<class-string, class-string>
     */
    protected array $repositories = [
        // Cốt lõi
        \App\Repositories\Contracts\UserRepositoryInterface::class => \App\Repositories\Eloquent\UserRepository::class,
        \App\Repositories\Contracts\ProductRepositoryInterface::class => \App\Repositories\Eloquent\ProductRepository::class,
        \App\Repositories\Contracts\OrderRepositoryInterface::class => \App\Repositories\Eloquent\OrderRepository::class,
        \App\Repositories\Contracts\CategoryRepositoryInterface::class => \App\Repositories\Eloquent\CategoryRepository::class,
        \App\Repositories\Contracts\CartItemRepositoryInterface::class => \App\Repositories\Eloquent\CartItemRepository::class,
        \App\Repositories\Contracts\WishlistRepositoryInterface::class => \App\Repositories\Eloquent\WishlistRepository::class,
        \App\Repositories\Contracts\ProductReviewRepositoryInterface::class => \App\Repositories\Eloquent\ProductReviewRepository::class,
        \App\Repositories\Contracts\NotificationRepositoryInterface::class => \App\Repositories\Eloquent\NotificationRepository::class,
        \App\Repositories\Contracts\BlogRepositoryInterface::class => \App\Repositories\Eloquent\BlogRepository::class,
        \App\Repositories\Contracts\PaymentRepositoryInterface::class => \App\Repositories\Eloquent\PaymentRepository::class,

        // Bổ sung từ Scopes
        \App\Repositories\Contracts\StockItemRepositoryInterface::class => \App\Repositories\Eloquent\StockItemRepository::class,
        \App\Repositories\Contracts\MailRepositoryInterface::class => \App\Repositories\Eloquent\MailRepository::class,
        \App\Repositories\Contracts\MailRecipientRepositoryInterface::class => \App\Repositories\Eloquent\MailRecipientRepository::class,
        \App\Repositories\Contracts\BannerRepositoryInterface::class => \App\Repositories\Eloquent\BannerRepository::class,
        \App\Repositories\Contracts\ShippingAddressRepositoryInterface::class => \App\Repositories\Eloquent\ShippingAddressRepository::class,
        \App\Repositories\Contracts\UserAddressRepositoryInterface::class => \App\Repositories\Eloquent\UserAddressRepository::class,
        \App\Repositories\Contracts\ImageRepositoryInterface::class => \App\Repositories\Eloquent\ImageRepository::class,
        \App\Repositories\Contracts\ImageableRepositoryInterface::class => \App\Repositories\Eloquent\ImageableRepository::class,
        \App\Repositories\Contracts\CategoryableRepositoryInterface::class => \App\Repositories\Eloquent\CategoryableRepository::class,
        \App\Repositories\Contracts\OrderItemRepositoryInterface::class => \App\Repositories\Eloquent\OrderItemRepository::class,
    ];

    /**
     * Đăng ký binding repository.
     */
    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    public function boot(): void
    {
        //
    }
}
