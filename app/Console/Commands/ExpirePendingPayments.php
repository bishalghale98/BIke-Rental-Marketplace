<?php

namespace App\Console\Commands;

use App\Enums\BookingStatusEnum;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirePendingPayments extends Command
{
    protected $signature = 'bookings:expire-pending-payments';

    protected $description = 'Expire bookings where payment was not completed within the timeout window';

    public function handle(): int
    {
        $timeoutMinutes = config('marketplace.payment_timeout_minutes', 15);

        $expired = Booking::pendingPayment()
            ->where('created_at', '<', now()->subMinutes($timeoutMinutes))
            ->get();

        $count = 0;
        foreach ($expired as $booking) {
            $booking->status = BookingStatusEnum::Expired;
            $booking->save();

            $booking->payments()->where('status', 'pending')->update(['status' => 'failed']);

            $count++;
        }

        Log::info("Expired {$count} pending payment booking(s).");

        $this->info("Expired {$count} booking(s).");

        return static::SUCCESS;
    }
}
