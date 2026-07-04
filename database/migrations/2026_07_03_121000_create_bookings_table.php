<?php

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 20)->unique();
            $table->foreignId('customer_id')->constrained('customer_profiles')->cascadeOnDelete();
            $table->foreignId('bike_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('hourly_price', 10, 2)->nullable();
            $table->decimal('daily_price', 10, 2)->nullable();
            $table->decimal('weekly_price', 10, 2)->nullable();
            $table->decimal('hourly_discount', 5, 2)->default(0);
            $table->decimal('daily_discount', 5, 2)->default(0);
            $table->decimal('weekly_discount', 5, 2)->default(0);
            $table->decimal('total_hours', 10, 2)->default(0);
            $table->decimal('total_days', 10, 2)->default(0);
            $table->decimal('total_weeks', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status', 20)->default(BookingStatusEnum::Pending->value);
            $table->string('cancellation_reason')->nullable();
            $table->string('cancelled_by', 20)->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
