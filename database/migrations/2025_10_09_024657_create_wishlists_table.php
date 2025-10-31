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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            //Cascade delete because a wishlist belongs to a specific user
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            //Cascade delete because wishlists depend on existing products
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            //Null on delete to avoid errors when a variant is deleted
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'product_id', 'variant_id'], 'wishlist_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
