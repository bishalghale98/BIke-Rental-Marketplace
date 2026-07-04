<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('refund_amount', 10, 2)->nullable()->after('cancelled_by');
            $table->timestamp('refund_paid_at')->nullable()->after('refund_amount');
            $table->decimal('late_fee', 10, 2)->nullable()->after('refund_paid_at');
            $table->timestamp('late_fee_paid_at')->nullable()->after('late_fee');
            $table->timestamp('extended_until')->nullable()->after('late_fee_paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['refund_amount', 'refund_paid_at', 'late_fee', 'late_fee_paid_at', 'extended_until']);
        });
    }
};
