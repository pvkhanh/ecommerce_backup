<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('imageables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')
                ->constrained('images')
                ->cascadeOnDelete(); // khi xóa image thì xóa luôn liên kết

            $table->unsignedBigInteger('imageable_id');
            $table->string('imageable_type');
            $table->boolean('is_main')->default(false)->index();
            $table->integer('position')->default(0);
            $table->timestamps();

            // Chỉ mục và unique key để tránh trùng
            $table->index(['imageable_type', 'imageable_id']);
            $table->unique(['image_id', 'imageable_id', 'imageable_type'], 'imageable_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imageables');
    }
};
