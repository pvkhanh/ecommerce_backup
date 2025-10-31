<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\MailType;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 255);
            $table->text('content');
            $table->string('template_key', 100)->nullable();
            $table->enum('type', MailType::values())
                ->default(MailType::System->value)
                ->comment('Email type');
            $table->string('sender_email', 255)->nullable();
            $table->json('variables')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};