<?php

namespace Tests\Feature\Repositories;

use App\Repositories\Eloquent\OrderRepository;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderRepositoryTest extends BaseRepositoryTestCase
{
    protected function setUp(): void
    {
        $this->repository = app(OrderRepository::class);
        parent::setUp();
    }

    /** @test */
    public function it_can_perform_basic_crud()
    {
        parent::it_can_perform_basic_crud();
    }

    /** @test */
    public function it_can_find_orders_by_user()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $orderA = Order::factory()->create(['user_id' => $userA->id]);
        $orderB = Order::factory()->create(['user_id' => $userB->id]);

        $results = $this->repository->forUser($userA->id);

        $this->assertTrue($results->contains('id', $orderA->id));
        $this->assertFalse($results->contains('id', $orderB->id));
    }

    /** @test */
    public function it_can_calculate_order_total()
    {
        $order = Order::factory()->create();
        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 200]);

        OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product1->id, 'quantity' => 2]);
        OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product2->id, 'quantity' => 1]);

        $total = $this->repository->calculateTotal($order->id);

        $this->assertEquals(400, $total);
    }

    /** @test */
    public function it_can_filter_orders_by_status()
    {
        $pending = Order::factory()->create(['status' => 'pending']);
        $completed = Order::factory()->create(['status' => 'completed']);

        $results = $this->repository->withStatus('completed');

        $this->assertTrue($results->contains('id', $completed->id));
        $this->assertFalse($results->contains('id', $pending->id));
    }

//     /** @test */
//     public function it_can_mark_order_as_paid()
//     {
//         $order = Order::factory()->create(['is_paid' => false]);

//         $this->repository->markAsPaid($order->id);
//         $updated = $this->repository->find($order->id);

//         $this->assertTrue($updated->is_paid);
//     }
}
