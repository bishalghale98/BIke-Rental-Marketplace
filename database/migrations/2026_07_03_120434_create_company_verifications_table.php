<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->unique()->constrained('company_profiles')->cascadeOnDelete();
            $table->string('registration_certificate')->nullable();
            $table->string('pan_certificate')->nullable();
            $table->string('owner_citizenship')->nullable();
            $table->string('owner_photo')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('rejected_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_verifications');
    }
};
