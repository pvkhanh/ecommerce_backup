<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            //Restrict on delete to prevent deleting orders that have payment records
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete();
            $table->enum('payment_method', PaymentMethod::values())
                ->comment('Payment method');
            $table->string('transaction_id', 100)->nullable()->unique()
                ->comment('Transaction code');
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', PaymentStatus::values())
                ->default(PaymentStatus::Pending->value)
                ->comment('Payment status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
