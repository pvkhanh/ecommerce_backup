<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            //Cascade delete because a cart should not exist after the user is deleted
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            //Cascade delete because cart items depend on existing products
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            //Null on delete to avoid breaking cart items when a variant is removed
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->boolean('selected')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'product_id', 'variant_id'], 'cart_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
