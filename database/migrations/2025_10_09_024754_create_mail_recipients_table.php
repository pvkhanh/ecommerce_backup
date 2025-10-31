<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\MailRecipientStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mail_recipients', function (Blueprint $table) {
            $table->id();
            //Restrict deletion to retain mail recipient log even if mail template is deleted
            $table->foreignId('mail_id')->constrained('mails')->restrictOnDelete();
            //Null on delete to keep email logs after the user account is deleted
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email', 255);
            $table->string('name', 100)->nullable();
            $table->enum('status', MailRecipientStatus::values())
                ->default(MailRecipientStatus::Pending->value);
            $table->text('error_log')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_recipients');
    }
};
