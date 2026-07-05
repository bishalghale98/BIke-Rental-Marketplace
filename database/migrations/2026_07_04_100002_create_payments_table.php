<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // deposit, remaining, late_fee, refund
            $table->string('gateway', 20); // khalti, esewa
            $table->string('gateway_txn_id')->nullable();
            $table->string('status', 20); // pending, completed, failed, refunded
            $table->decimal('amount', 10, 2);
            $table->unsignedTinyInteger('attempt_number')->default(1);
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
