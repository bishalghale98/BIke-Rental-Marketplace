<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_confirmed',
            'title' => 'Booking Confirmed',
            'message' => "Booking #{$this->booking->booking_number} for {$this->booking->bike->name} has been confirmed.",
            'booking_id' => $this->booking->id,
            'url' => route('customer.bookings.show', $this->booking),
        ];
    }
}
