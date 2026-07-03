<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('owner_name');
            $table->string('registration_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('opening_hours')->nullable();
            $table->json('social_links')->nullable();
            $table->string('verification_status', 20)->default('pending');
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
