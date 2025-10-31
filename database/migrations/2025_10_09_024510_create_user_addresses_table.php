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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            //Address belongs to 1 user → when user deletes, address must be deleted too.
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('Cascade delete because addresses should not exist after the user is deleted');
            $table->string('receiver_name', 100);
            $table->string('phone', 20);
            $table->string('address', 255);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->string('postal_code', 20)->nullable();
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
