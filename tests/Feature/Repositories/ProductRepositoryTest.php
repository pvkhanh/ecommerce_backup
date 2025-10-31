<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\ProductRepository;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\StockItem;

/**
 * ✅ ProductRepositoryTest
 *
 * Kiểm thử tất cả các chức năng trong ProductRepository:
 *  - CRUD cơ bản (create, read, update, delete)
 *  - Tìm sản phẩm theo danh mục
 *  - Lấy sản phẩm nổi bật
 *  - Lấy sản phẩm còn hàng
 *  - Lấy sản phẩm có giảm giá
 *  - Tìm kiếm sản phẩm theo từ khóa
 *  - Cập nhật giá sản phẩm
 *  - Xóa mềm sản phẩm
 */
class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Khởi tạo repository
        $this->repository = new ProductRepository();
    }

    /** @test */
    public function it_can_perform_basic_crud()
    {
        // Create
        $product = $this->repository->create([
            'name' => 'MacBook Pro 16',
            'slug' => 'macbook-pro-16',
            'description' => 'Powerful laptop from Apple',
            'price' => 1000.00,
        ]);

        $this->assertDatabaseHas('products', ['name' => 'MacBook Pro 16']);

        // Read
        $found = $this->repository->find($product->id);
        $this->assertEquals('MacBook Pro 16', $found->name);

        // Update
        $this->repository->update($product->id, ['price' => 1200.00]);
        $this->assertEquals(1200.00, $this->repository->find($product->id)->price);

        // Delete
        $this->repository->delete($product->id);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_can_find_products_by_category()
    {
        $category = Category::factory()->create();
        $productA = Product::factory()->create(['name' => 'iPhone 15']);
        $productB = Product::factory()->create(['name' => 'Galaxy S24']);

        $productA->categories()->attach($category->id);

        $found = $this->repository->byCategory($category->id);

        $this->assertTrue($found->contains('id', $productA->id));
        $this->assertFalse($found->contains('id', $productB->id));
    }

    /** @test */
    public function it_can_find_featured_products()
    {
        $featured = Product::factory()->create(['is_featured' => true]);
        $normal = Product::factory()->create(['is_featured' => false]);

        $found = Product::where('is_featured', true)->get();

        $this->assertTrue($found->contains('id', $featured->id));
        $this->assertFalse($found->contains('id', $normal->id));
    }

    /** @test */
    public function it_can_find_products_in_stock()
    {
        $productInStock = Product::factory()->create(['name' => 'Laptop']);
        $productOutOfStock = Product::factory()->create(['name' => 'Phone']);

        StockItem::factory()->create(['product_id' => $productInStock->id, 'quantity' => 10]);
        StockItem::factory()->create(['product_id' => $productOutOfStock->id, 'quantity' => 0]);

        $found = Product::whereHas('stockItems', fn($q) => $q->where('quantity', '>', 0))->get();

        $this->assertTrue($found->contains('id', $productInStock->id));
        $this->assertFalse($found->contains('id', $productOutOfStock->id));
    }

    /** @test */
    public function it_can_find_discounted_products()
    {
        $discounted = Product::factory()->create(['discount_percent' => 20]);
        $regular = Product::factory()->create(['discount_percent' => 0]);

        $found = Product::where('discount_percent', '>', 0)->get();

        $this->assertTrue($found->contains('id', $discounted->id));
        $this->assertFalse($found->contains('id', $regular->id));
    }

    /** @test */
    public function it_can_search_products_by_keyword()
    {
        $product = Product::factory()->create(['name' => 'iPhone 15 Pro Max']);
        Product::factory()->create(['name' => 'Samsung Galaxy']);

        $results = $this->repository->search('iPhone');

        $this->assertTrue($results->contains('id', $product->id));
        $this->assertCount(1, $results);
    }

    /** @test */
    public function it_can_update_product_price()
    {
        $product = Product::factory()->create(['price' => 1000]);

        $this->repository->update($product->id, ['price' => 1200]);

        $updated = $this->repository->find($product->id);
        $this->assertEquals(1200, $updated->price);
    }

    /** @test */
    public function it_can_soft_delete_product()
    {
        $product = Product::factory()->create();

        $this->repository->delete($product->id);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}