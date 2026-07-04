<?php

namespace App\Policies;

use App\Models\Bike;
use App\Models\User;

class BikePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Bike $bike): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isCompany() && $user->company?->verification_status === 'verified';
    }

    public function update(User $user, Bike $bike): bool
    {
        return $user->isCompany() && $bike->company_id === $user->company?->id;
    }

    public function delete(User $user, Bike $bike): bool
    {
        return $user->isCompany() && $bike->company_id === $user->company?->id;
    }
}
