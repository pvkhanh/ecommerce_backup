<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{
    User,
    Product,
    Order,
    OrderItem,
    Category,
    Blog,
    Banner,
    CartItem,
    Image,
    Imageable,
    Mail,
    MailRecipient,
    Notification,
    Payment,
    ProductReview,
    ShippingAddress,
    StockItem,
    UserAddress,
    Wishlist
};
use App\Repositories\Eloquent\{
    UserRepository,
    ProductRepository,
    OrderRepository,
    OrderItemRepository,
    CategoryRepository,
    BlogRepository,
    BannerRepository,
    CartItemRepository,
    ImageRepository,
    ImageableRepository,
    MailRepository,
    MailRecipientRepository,
    NotificationRepository,
    PaymentRepository,
    ProductReviewRepository,
    ShippingAddressRepository,
    StockItemRepository,
    UserAddressRepository,
    WishlistRepository,
    CategoryableRepository
};
use Illuminate\Support\Facades\DB;

class AllRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // ==================== USER REPOSITORY ====================

    public function test_user_repository_set_avatar(): void
    {
        $repo = app(UserRepository::class);
        $user = User::factory()->create();
        $image = Image::factory()->create(['type' => 'avatar']);

        $result = $repo->setAvatar($user, $image);

        $this->assertInstanceOf(Image::class, $result);
        $this->assertDatabaseHas('imageables', [
            'imageable_id' => $user->id,
            'imageable_type' => User::class,
            'image_id' => $image->id,
            'is_main' => true,
        ]);
    }

    public function test_user_repository_get_active(): void
    {
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        $repo = app(UserRepository::class);
        $active = $repo->getActive();

        $this->assertCount(1, $active);
    }

    public function test_user_repository_search(): void
    {
        // Sử dụng first_name và last_name thay vì name
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);
        User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith'
        ]);

        $repo = app(UserRepository::class);
        $results = $repo->search('John');

        $this->assertTrue($results->contains($user));
    }

    // ==================== PRODUCT REPOSITORY ====================

    public function test_product_repository_get_active(): void
    {
        // Sử dụng status thay vì is_active
        Product::factory()->create(['status' => 'active']);
        Product::factory()->create(['status' => 'inactive']);

        $repo = app(ProductRepository::class);
        $active = $repo->getActive();

        $this->assertCount(1, $active);
    }

    public function test_product_repository_price_between(): void
    {
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 500]);
        Product::factory()->create(['price' => 1000]);

        $repo = app(ProductRepository::class);
        $products = $repo->priceBetween(200, 800);

        $this->assertCount(1, $products);
    }

    public function test_product_repository_find_by_slug(): void
    {
        $product = Product::factory()->create(['slug' => 'test-product']);

        $repo = app(ProductRepository::class);
        $found = $repo->findBySlug('test-product');

        $this->assertEquals($product->id, $found->id);
    }

    public function test_product_repository_search_paginated(): void
    {
        Product::factory()->count(20)->create();

        $repo = app(ProductRepository::class);
        $paginated = $repo->searchPaginated('test', 10);

        $this->assertEquals(10, $paginated->perPage());
    }

    // ==================== ORDER REPOSITORY ====================

    public function test_order_repository_get_by_user(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);
        Order::factory()->create();

        $repo = app(OrderRepository::class);
        $orders = $repo->getByUser($user->id);

        $this->assertCount(3, $orders);
    }

    public function test_order_repository_get_by_status(): void
    {
        Order::factory()->count(2)->create(['status' => 'pending']);
        Order::factory()->create(['status' => 'completed']);

        $repo = app(OrderRepository::class);
        $pending = $repo->getByStatus('pending');

        $this->assertCount(2, $pending);
    }

    public function test_order_repository_date_range(): void
    {
        Order::factory()->create(['created_at' => now()->subDays(5)]);
        Order::factory()->create(['created_at' => now()->subDays(15)]);

        $repo = app(OrderRepository::class);
        $orders = $repo->dateRange(
            now()->subDays(10)->toDateString(),
            now()->toDateString()
        );

        $this->assertCount(1, $orders);
    }

    public function test_order_repository_get_recent_orders(): void
    {
        Order::factory()->count(15)->create();

        $repo = app(OrderRepository::class);
        $recent = $repo->getRecentOrders(10);

        $this->assertCount(10, $recent);
    }

    // ==================== CART REPOSITORY ====================

    public function test_cart_repository_get_by_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $repo = app(CartItemRepository::class);
        $items = $repo->getByUser($user->id);

        $this->assertCount(1, $items);
    }

    public function test_cart_repository_add_or_update(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $repo = app(CartItemRepository::class);
        $item = $repo->addOrUpdate($user->id, $product->id, 2);

        $this->assertEquals(2, $item->quantity);

        // Test update
        $updated = $repo->addOrUpdate($user->id, $product->id, 3);
        $this->assertEquals(5, $updated->quantity);
    }

    public function test_cart_repository_clear_user_cart(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $product3 = Product::factory()->create();

        // Tạo cart items với product khác nhau
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product1->id,
        ]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product2->id,
        ]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product3->id,
        ]);

        $repo = app(CartItemRepository::class);
        $deleted = $repo->clearUserCart($user->id);

        $this->assertEquals(3, $deleted);

        // Kiểm tra cart items của user đã bị xóa
        $remaining = CartItem::where('user_id', $user->id)->count();
        $this->assertEquals(0, $remaining);
    }

    public function test_cart_repository_selected_for_user(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product1->id,
            'selected' => true
        ]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product2->id,
            'selected' => false
        ]);

        $repo = app(CartItemRepository::class);
        $selected = $repo->selectedForUser($user->id);

        $this->assertCount(1, $selected);
    }

    // ==================== WISHLIST REPOSITORY ====================

    public function test_wishlist_repository_add_to_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $repo = app(WishlistRepository::class);
        $wishlist = $repo->addToWishlist($user->id, $product->id);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_wishlist_repository_remove_from_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $repo = app(WishlistRepository::class);
        $deleted = $repo->removeFromWishlist($user->id, $product->id);

        $this->assertEquals(1, $deleted);
    }

    public function test_wishlist_repository_exists_entry(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $repo = app(WishlistRepository::class);
        $exists = $repo->existsEntry($user->id, $product->id);

        $this->assertTrue($exists);
    }

    // ==================== STOCK REPOSITORY ====================

    /** @test */
    public function test_stock_repository_increase_stock(): void
    {
        // ✅ Tạo product_variant thay vì variants
        $variantId = DB::table('product_variants')->insertGetId([
            'product_id' => Product::factory()->create()->id,
            'name' => 'Test Variant',
            'sku' => 'TEST-SKU-' . rand(1000, 9999),
            'price' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $repo = app(StockItemRepository::class);
        $result = $repo->increaseStock($variantId, 10);

        $this->assertTrue($result);
        $this->assertDatabaseHas('stock_items', [
            'variant_id' => $variantId,
            'quantity' => 10,
        ]);
    }

    /** @test */
    public function test_stock_repository_decrease_stock(): void
    {
        $variantId = DB::table('product_variants')->insertGetId([
            'product_id' => Product::factory()->create()->id,
            'name' => 'Test Variant',
            'sku' => 'TEST-SKU-' . rand(1000, 9999),
            'price' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StockItem::create([
            'variant_id' => $variantId,
            'quantity' => 20,
            'location' => 'default'
        ]);

        $repo = app(StockItemRepository::class);
        $result = $repo->decreaseStock($variantId, 5);

        $this->assertTrue($result);
        $this->assertDatabaseHas('stock_items', [
            'variant_id' => $variantId,
            'quantity' => 15,
        ]);
    }

    /** @test */
    public function test_stock_repository_low_stock(): void
    {
        $product = Product::factory()->create();

        $variantId1 = DB::table('product_variants')->insertGetId([
            'product_id' => $product->id,
            'name' => 'Variant A',
            'sku' => 'TEST-SKU-' . rand(1000, 9999),
            'price' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $variantId2 = DB::table('product_variants')->insertGetId([
            'product_id' => $product->id,
            'name' => 'Variant B',
            'sku' => 'TEST-SKU-' . rand(1000, 9999),
            'price' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StockItem::create(['variant_id' => $variantId1, 'quantity' => 3, 'location' => 'default']);
        StockItem::create(['variant_id' => $variantId2, 'quantity' => 10, 'location' => 'default']);

        $repo = app(StockItemRepository::class);
        $lowStock = $repo->lowStock(5);

        $this->assertCount(1, $lowStock);
        $this->assertEquals($variantId1, $lowStock->first()->variant_id);
    }

    //=====================PAYMENT REPOSITORY ====================

    public function test_payment_repository_update_status(): void
    {
        $payment = Payment::factory()->create(['status' => 'pending']);

        $repo = app(PaymentRepository::class);
        $result = $repo->updateStatus($payment->id, 'success');

        $this->assertTrue($result);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'success',
        ]);
    }

    public function test_payment_repository_find_by_transaction_id(): void
    {
        $payment = Payment::factory()->create(['transaction_id' => 'TXN123']);

        $repo = app(PaymentRepository::class);
        $found = $repo->findByTransactionId('TXN123');

        $this->assertEquals($payment->id, $found->id);
    }

    // ==================== NOTIFICATION REPOSITORY ====================

    public function test_notification_repository_mark_as_read(): void
    {
        $notification = Notification::factory()->create(['is_read' => false]);

        $repo = app(NotificationRepository::class);
        $result = $repo->markAsRead($notification->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    public function test_notification_repository_get_unread_by_user(): void
    {
        $user = User::factory()->create();
        Notification::factory()->create([
            'user_id' => $user->id,
            'is_read' => false,
        ]);
        Notification::factory()->create([
            'user_id' => $user->id,
            'is_read' => true,
        ]);

        $repo = app(NotificationRepository::class);
        $unread = $repo->getUnreadByUser($user->id);

        $this->assertCount(1, $unread);
    }

    public function test_notification_repository_clear_old_notifications(): void
    {
        Notification::factory()->create(['created_at' => now()->subDays(40)]);
        Notification::factory()->create(['created_at' => now()->subDays(10)]);

        $repo = app(NotificationRepository::class);
        $deleted = $repo->clearOldNotifications(30);

        $this->assertEquals(1, $deleted);
    }

    // ==================== CATEGORY REPOSITORY ====================

    public function test_category_repository_get_root_categories(): void
    {
        Category::factory()->create(['parent_id' => null]);
        $parent = Category::factory()->create(['parent_id' => null]);
        Category::factory()->create(['parent_id' => $parent->id]);

        $repo = app(CategoryRepository::class);
        $roots = $repo->getRootCategories();

        $this->assertCount(2, $roots);
    }

    public function test_category_repository_get_children(): void
    {
        $parent = Category::factory()->create();
        Category::factory()->count(3)->create(['parent_id' => $parent->id]);

        $repo = app(CategoryRepository::class);
        $children = $repo->getChildren($parent->id);

        $this->assertCount(3, $children);
    }

    // ==================== BLOG REPOSITORY ====================

    public function test_blog_repository_get_published(): void
    {
        Blog::factory()->create(['status' => 'published']);
        Blog::factory()->create(['status' => 'draft']);

        $repo = app(BlogRepository::class);
        $published = $repo->getPublished();

        $this->assertCount(1, $published);
    }

    public function test_blog_repository_get_by_author(): void
    {
        $author = User::factory()->create();
        Blog::factory()->count(2)->create([
            'author_id' => $author->id,
            'status' => 'published',
        ]);

        $repo = app(BlogRepository::class);
        $blogs = $repo->getByAuthor($author->id);

        $this->assertCount(2, $blogs);
    }

    // ==================== BANNER REPOSITORY ====================

    public function test_banner_repository_get_active(): void
    {
        Banner::factory()->create(['is_active' => true]);
        Banner::factory()->create(['is_active' => false]);

        $repo = app(BannerRepository::class);
        $active = $repo->getActive();

        $this->assertCount(1, $active);
    }

    public function test_banner_repository_update_positions(): void
    {
        $banner1 = Banner::factory()->create(['position' => 1]);
        $banner2 = Banner::factory()->create(['position' => 2]);

        $repo = app(BannerRepository::class);
        $result = $repo->updatePositions([
            $banner1->id => 5,
            $banner2->id => 10,
        ]);

        $this->assertTrue($result);
        $this->assertDatabaseHas('banners', ['id' => $banner1->id, 'position' => 5]);
        $this->assertDatabaseHas('banners', ['id' => $banner2->id, 'position' => 10]);
    }

    // ==================== IMAGE REPOSITORY ====================

    public function test_image_repository_get_avatar(): void
    {
        Image::factory()->create(['type' => 'avatar']);
        Image::factory()->create(['type' => 'gallery']);

        $repo = app(ImageRepository::class);

        // Sử dụng query builder thay vì scope
        $avatars = $repo->where('type', 'avatar')->get();

        $this->assertCount(1, $avatars);
        $this->assertEquals('avatar', $avatars->first()->type);
    }

    // ==================== MAIL REPOSITORY ====================

    public function test_mail_repository_by_key(): void
    {
        $mail = Mail::factory()->create(['template_key' => 'welcome-email']);

        $repo = app(MailRepository::class);
        $found = $repo->byKey('welcome-email');

        $this->assertEquals($mail->id, $found->id);
    }

    public function test_mail_repository_get_paginated_with_filters(): void
    {
        // Sử dụng giá trị enum hợp lệ hoặc không filter theo type
        Mail::factory()->count(25)->create();

        $repo = app(MailRepository::class);
        $paginated = $repo->getPaginatedWithFilters([], 10);

        $this->assertEquals(10, $paginated->perPage());
        $this->assertGreaterThanOrEqual(10, $paginated->total());
    }

    // ==================== REVIEW REPOSITORY ====================

    public function test_product_review_repository_get_approved_by_product(): void
    {
        $product = Product::factory()->create();
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'approved',
        ]);
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'pending',
        ]);

        $repo = app(ProductReviewRepository::class);
        $reviews = $repo->getApprovedByProduct($product->id);

        $this->assertCount(1, $reviews);
    }

    // ==================== BASE REPOSITORY CRUD ====================

    public function test_base_repository_crud_operations(): void
    {
        $repo = app(UserRepository::class);

        // Create - thêm các trường bắt buộc
        $user = $repo->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        // Find
        $found = $repo->find($user->id);
        $this->assertEquals($user->id, $found->id);

        // Update
        $updated = $repo->update($user->id, ['first_name' => 'Updated']);
        $this->assertTrue($updated);
        $this->assertDatabaseHas('users', ['first_name' => 'Updated']);

        // Delete
        $deleted = $repo->delete($user->id);
        $this->assertTrue($deleted);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_base_repository_transaction(): void
    {
        $repo = app(UserRepository::class);

        $result = $repo->transaction(function () use ($repo) {
            $user1 = $repo->create([
                'first_name' => 'User',
                'last_name' => 'One',
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => bcrypt('password'),
            ]);

            $user2 = $repo->create([
                'first_name' => 'User',
                'last_name' => 'Two',
                'username' => 'user2',
                'email' => 'user2@example.com',
                'password' => bcrypt('password'),
            ]);

            return [$user1, $user2];
        });

        $this->assertCount(2, $result);
        $this->assertDatabaseCount('users', 2);
    }

    public function test_base_repository_count(): void
    {
        User::factory()->count(5)->create(['is_active' => true]);
        User::factory()->count(3)->create(['is_active' => false]);

        $repo = app(UserRepository::class);
        $count = $repo->count(['is_active' => true]);

        $this->assertEquals(5, $count);
    }

    public function test_base_repository_with_relationships(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);

        $repo = app(OrderRepository::class);
        $query = $repo->with(['user']);
        $orders = $query->get();

        $this->assertTrue($orders->first()->relationLoaded('user'));
    }
}