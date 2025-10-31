<?php

namespace Tests\Feature\Repositories;

use App\Repositories\Contracts\UserRepositoryInterface;
use Tests\TestCase;

class UserRepositoryTest extends BaseRepositoryTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Gán repository để test base class dùng
        $this->repository = $this->app->make(UserRepositoryInterface::class);
    }

    /** @test */
    public function it_can_perform_basic_crud()
    {
        // Test cơ bản, ví dụ tạo user
        $user = $this->repository->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123456'),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $found = $this->repository->find($user->id);
        $this->assertEquals('Test User', $found->name);

        $this->repository->delete($user->id);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }
}
