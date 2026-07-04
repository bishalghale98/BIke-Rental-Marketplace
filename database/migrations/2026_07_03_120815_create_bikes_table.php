<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bikes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_profiles')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('bike_categories')->nullOnDelete();
            $table->string('name');
            $table->string('brand');
            $table->string('model');
            $table->year('year')->nullable();
            $table->string('engine_capacity', 20)->nullable();
            $table->string('fuel_type', 30)->nullable();
            $table->string('transmission', 30)->nullable();
            $table->string('mileage', 20)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('bike_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('vin')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->json('specifications')->nullable();
            $table->json('rental_rules')->nullable();
            $table->decimal('hourly_price', 10, 2)->nullable();
            $table->decimal('daily_price', 10, 2)->nullable();
            $table->decimal('weekly_price', 10, 2)->nullable();
            $table->string('status', 20)->default('active');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bikes');
    }
};
