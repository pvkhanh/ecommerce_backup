<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ReviewStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            //Cascade delete because reviews have no meaning without the product
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            //Null on delete to keep anonymous reviews after user deletion
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->enum('status', ReviewStatus::values())->default(ReviewStatus::Pending->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
