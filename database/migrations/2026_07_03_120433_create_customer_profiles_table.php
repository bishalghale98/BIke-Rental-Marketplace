<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('citizenship_number')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('current_address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('verification_status', 20)->default('unverified');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};
