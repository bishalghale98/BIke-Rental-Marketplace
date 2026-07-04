<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingCreatedNotification extends Notification
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
            'type' => 'booking_created',
            'title' => 'New Booking Received',
            'message' => "New booking #{$this->booking->booking_number} for {$this->booking->bike->name}.",
            'booking_id' => $this->booking->id,
            'url' => route('company.bookings.show', $this->booking),
        ];
    }
}
