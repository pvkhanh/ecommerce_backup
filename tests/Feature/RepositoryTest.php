<?php

namespace Tests\Feature\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use ReflectionClass;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test] // ✅ Dùng attribute mới, bỏ deprecated doc-comment
    public function all_repositories_crud_and_logic(): void
    {
        $repoPath = base_path('app/Repositories/Eloquent');
        $files = glob($repoPath . '/*.php');

        foreach ($files as $file) {
            $className = 'App\\Repositories\\Eloquent\\' . basename($file, '.php');

            if (!class_exists($className)) {
                echo "⚠️  Bỏ qua {$className}: không tồn tại.\n";
                continue;
            }

            $repository = app($className);

            // Bỏ qua BaseRepository hoặc class không có hàm model()
            if (!method_exists($repository, 'model')) {
                echo "⚠️  Bỏ qua {$className}: không có hàm model().\n";
                continue;
            }

            // Truy cập protected model() bằng Reflection
            $reflection = new ReflectionClass($repository);
            $method = $reflection->getMethod('model');
            $method->setAccessible(true);
            $modelClass = $method->invoke($repository);

            if (!class_exists($modelClass)) {
                echo "⚠️  Bỏ qua {$className}: không tìm thấy Model {$modelClass}.\n";
                continue;
            }

            echo "🧩 Testing {$className}...\n";

            $model = new $modelClass();

            // Kiểm tra bảng tồn tại
            if (!Schema::hasTable($model->getTable())) {
                echo "⚠️  Bỏ qua {$className}: bảng {$model->getTable()} chưa tồn tại.\n";
                continue;
            }

            // Nếu có Factory thì dùng để tạo dữ liệu
            if (method_exists($modelClass, 'factory')) {
                $item = $modelClass::factory()->create();
            } else {
                // Nếu không có factory, tạo mẫu đơn giản
                $fillable = $model->getFillable();
                if (empty($fillable)) {
                    echo "⚠️  Bỏ qua {$className}: không có fillable fields.\n";
                    continue;
                }

                $data = [];
                foreach ($fillable as $field) {
                    $data[$field] = fake()->word();
                }
                $item = $repository->create($data);
            }

            try {
                // 🧠 CRUD cơ bản
                $found = $repository->find($item->id);
                $this->assertNotNull($found, "{$className} ->find() thất bại");

                // Cập nhật bản ghi
                $repository->update($item->id, ['updated_at' => now()]);
                $updated = $repository->find($item->id);
                $this->assertNotNull($updated, "{$className} ->update() thất bại");

                // Xóa bản ghi
                $repository->delete($item->id);

                if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
                    // Nếu Model có SoftDeletes, kiểm tra soft deleted
                    $this->assertSoftDeleted($model->getTable(), ['id' => $item->id]);
                } else {
                    // Nếu không có soft delete, đảm bảo xóa cứng
                    $this->assertDatabaseMissing($model->getTable(), ['id' => $item->id]);
                }

                echo "✅ {$className} PASSED CRUD\n";

            } catch (\Throwable $e) {
                echo "💥 Lỗi tại {$className}: {$e->getMessage()}\n";
                $this->fail($e->getMessage());
            }
        }
    }
}