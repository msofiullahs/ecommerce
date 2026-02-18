<?php

namespace App\Console\Commands;

use App\Mail\StockAlertMail;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class StockAlertCommand extends Command
{
    protected $signature = 'stock:check-alerts';
    protected $description = 'Check for low stock items and send alerts';

    public function handle(StockService $stockService): int
    {
        $lowStockVariants = $stockService->getLowStockVariants();

        if ($lowStockVariants->isEmpty()) {
            $this->info('No low stock items found.');
            return self::SUCCESS;
        }

        $admins = User::role(['super-admin', 'admin', 'warehouse-staff'])->get();

        foreach ($lowStockVariants as $variant) {
            foreach ($admins as $admin) {
                Mail::to($admin->email)->queue(new StockAlertMail($variant));
            }
        }

        $this->info("Sent alerts for {$lowStockVariants->count()} low stock items.");

        return self::SUCCESS;
    }
}
