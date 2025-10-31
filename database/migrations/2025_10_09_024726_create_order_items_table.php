<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            //Cascade delete because order items must be removed with their order
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            //Null on delete to keep order history even if the product is deleted
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            //Null on delete because variant might be removed later but order history must remain
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
