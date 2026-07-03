<?php

namespace App\Enums;

enum AccountStatusEnum: string
{
    case Active = 'active';
    case PendingVerification = 'pending_verification';
    case Suspended = 'suspended';
    case Deactivated = 'deactivated';
    case Deleted = 'deleted';
}
