<?php

namespace App\Enums;

enum BookingStatusEnum: string
{
    case PendingPayment = 'pending_payment';
    case DepositPaid = 'deposit_paid';
    case Confirmed = 'confirmed';
    case PickedUp = 'picked_up';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
    case Refunded = 'refunded';
}
