<?php

namespace App\Listeners;

use App\Events\StockLow;
use App\Mail\StockAlertMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendStockAlert implements ShouldQueue
{
    public function handle(StockLow $event): void
    {
        $admins = User::role(['super-admin', 'admin', 'warehouse-staff'])->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(new StockAlertMail($event->variant));
        }
    }
}
