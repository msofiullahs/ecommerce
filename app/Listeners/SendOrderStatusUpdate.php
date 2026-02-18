<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Mail\OrderStatusChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusUpdate implements ShouldQueue
{
    public function handle(OrderStatusChanged $event): void
    {
        if ($event->order->user) {
            Mail::to($event->order->user->email)
                ->queue(new OrderStatusChangedMail($event->order, $event->oldStatus, $event->newStatus));
        }
    }
}
