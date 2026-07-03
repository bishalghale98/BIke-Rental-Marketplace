<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('license_number')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('license_front_image')->nullable();
            $table->string('license_back_image')->nullable();
            $table->string('citizenship_front')->nullable();
            $table->string('citizenship_back')->nullable();
            $table->string('selfie')->nullable();
            $table->string('status', 20)->default('unverified');
            $table->text('rejected_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_verifications');
    }
};
