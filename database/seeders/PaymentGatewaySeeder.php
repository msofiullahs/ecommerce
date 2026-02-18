<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'stripe',
                'display_name' => 'Stripe',
                'config' => [
                    'publishable_key' => '',
                    'secret_key' => '',
                    'webhook_secret' => '',
                ],
                'is_active' => false,
                'is_sandbox' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'midtrans',
                'display_name' => 'Midtrans',
                'config' => [
                    'server_key' => '',
                    'client_key' => '',
                    'is_sandbox' => true,
                ],
                'is_active' => false,
                'is_sandbox' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'doku',
                'display_name' => 'DOKU',
                'config' => [
                    'client_id' => '',
                    'shared_key' => '',
                    'is_sandbox' => true,
                ],
                'is_active' => false,
                'is_sandbox' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::create($gateway);
        }
    }
}
