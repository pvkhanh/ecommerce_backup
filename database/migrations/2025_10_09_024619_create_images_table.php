<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable(); // đường dẫn ảnh
            $table->string('type')->nullable(); // loại ảnh (banner, avatar, gallery, ...)
            $table->string('alt_text')->nullable(); // mô tả thay thế cho SEO
            $table->boolean('is_active')->default(true); // ảnh có được kích hoạt hay không
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};