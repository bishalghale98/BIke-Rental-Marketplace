<?php

namespace App\Policies;

use App\Models\Payout;
use App\Models\User;

class PayoutPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Payout $payout): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Payout $payout): bool
    {
        return $user->isAdmin();
    }

    public function approve(User $user, Payout $payout): bool
    {
        return $user->isAdmin() && $payout->status === 'pending';
    }

    public function markPaid(User $user, Payout $payout): bool
    {
        return $user->isAdmin() && $payout->status === 'processing';
    }

    public function markFailed(User $user, Payout $payout): bool
    {
        return $user->isAdmin() && in_array($payout->status, ['pending', 'processing']);
    }
}
