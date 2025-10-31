<?php

namespace Tests\Feature\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;
use Tests\TestCase;

/**
 * BaseRepositoryTestCase
 *
 * - Tự động kiểm tra CRUD cơ bản cho repository
 * - Lấy model từ hàm model() trong repository
 * - Hỗ trợ SoftDeletes, Factory, rollback transaction
 */
abstract class BaseRepositoryTestCase extends TestCase
{
    use RefreshDatabase;

    protected $repository;
    protected $modelClass;

    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->repository) {
            throw new \Exception('Bạn cần gán $repository trong test con.');
        }

        $reflection = new ReflectionClass($this->repository);
        $method = $reflection->getMethod('model');
        $method->setAccessible(true);
        $this->modelClass = $method->invoke($this->repository);

        if (!class_exists($this->modelClass)) {
            throw new \Exception("Không tìm thấy Model {$this->modelClass}");
        }

        if (!Schema::hasTable((new $this->modelClass())->getTable())) {
            $this->markTestSkipped("Bảng của {$this->modelClass} chưa tồn tại.");
        }
    }

    protected function createModelInstance()
    {
        $modelClass = $this->modelClass;
        if (method_exists($modelClass, 'factory')) {
            return $modelClass::factory()->create();
        }

        $model = new $modelClass();
        $data = [];
        foreach ($model->getFillable() as $field) {
            $data[$field] = fake()->word();
        }
        return $this->repository->create($data);
    }

    /** @test */
    public function it_can_perform_basic_crud()
    {
        $item = $this->createModelInstance();
        $this->assertNotNull($item->id, 'Không tạo được bản ghi mới.');

        $found = $this->repository->find($item->id);
        $this->assertNotNull($found, 'Không tìm thấy bản ghi.');

        $this->repository->update($item->id, ['updated_at' => now()]);
        $updated = $this->repository->find($item->id);
        $this->assertNotNull($updated, 'Cập nhật thất bại.');

        $this->repository->delete($item->id);
        $model = new $this->modelClass();
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
            $this->assertSoftDeleted($model->getTable(), ['id' => $item->id]);
        } else {
            $this->assertDatabaseMissing($model->getTable(), ['id' => $item->id]);
        }
    }
}
