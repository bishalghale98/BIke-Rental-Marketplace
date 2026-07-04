<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking, public string $cancelledBy) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isCustomer = $this->cancelledBy === 'customer';

        return [
            'type' => 'booking_cancelled',
            'title' => $isCustomer ? 'Booking Cancelled by Customer' : 'Booking Cancelled',
            'message' => "Booking #{$this->booking->booking_number} for {$this->booking->bike->name} has been cancelled.",
            'booking_id' => $this->booking->id,
            'url' => $isCustomer
                ? route('company.bookings.show', $this->booking)
                : route('customer.bookings.show', $this->booking),
        ];
    }
}
