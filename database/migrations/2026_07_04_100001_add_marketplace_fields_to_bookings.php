<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('commission_percent', 5, 2)->nullable()->after('discount_amount');
            $table->decimal('commission_amount', 10, 2)->nullable()->after('commission_percent');
            $table->decimal('company_earnings', 10, 2)->nullable()->after('commission_amount');
            $table->timestamp('deposit_paid_at')->nullable()->after('extended_until');
            $table->timestamp('remaining_paid_at')->nullable()->after('deposit_paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['commission_percent', 'commission_amount', 'company_earnings', 'deposit_paid_at', 'remaining_paid_at']);
        });
    }
};
