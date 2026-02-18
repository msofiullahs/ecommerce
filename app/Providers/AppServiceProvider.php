<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Events\StockLow;
use App\Listeners\SendOrderConfirmation;
use App\Listeners\SendOrderStatusUpdate;
use App\Listeners\SendStockAlert;
use Illuminate\Support\Facades\Event;
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
        // Super admin bypasses all permission checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Register event listeners
        Event::listen(OrderPlaced::class, SendOrderConfirmation::class);
        Event::listen(OrderStatusChanged::class, SendOrderStatusUpdate::class);
        Event::listen(StockLow::class, SendStockAlert::class);
    }
}
