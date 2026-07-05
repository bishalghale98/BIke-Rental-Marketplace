<?php

namespace App\Providers;

use App\Models\Payout;
use App\Policies\PayoutPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Payout::class, PayoutPolicy::class);
    }
}
