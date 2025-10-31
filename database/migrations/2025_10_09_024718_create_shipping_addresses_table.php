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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            //Cascade delete because shipping address exists only with its order
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('receiver_name', 100);
            $table->string('phone', 20);
            $table->text('address');
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->string('postal_code', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
