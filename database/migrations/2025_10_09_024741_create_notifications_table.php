<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\NotificationType;



return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            //Cascade delete because notifications are user-specific and should be removed with the user
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', NotificationType::values())
                ->comment('Notification Type');
            $table->string('title', 255);
            $table->text('message')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};