<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Order Confirmation',
                'slug' => 'order_confirmation',
                'subject' => ['en' => 'Order #{order_number} Confirmed', 'id' => 'Pesanan #{order_number} Dikonfirmasi'],
                'body' => [
                    'en' => '<h2>Thank you for your order!</h2><p>Dear {customer_name},</p><p>Your order <strong>#{order_number}</strong> has been confirmed.</p><p><strong>Order Total:</strong> {order_total}</p><p>We will notify you when your order is shipped.</p>',
                    'id' => '<h2>Terima kasih atas pesanan Anda!</h2><p>Yth {customer_name},</p><p>Pesanan Anda <strong>#{order_number}</strong> telah dikonfirmasi.</p><p><strong>Total Pesanan:</strong> {order_total}</p><p>Kami akan memberitahu Anda ketika pesanan dikirim.</p>',
                ],
                'variables' => ['order_number', 'customer_name', 'order_total', 'order_items', 'shipping_address'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Shipped',
                'slug' => 'order_shipped',
                'subject' => ['en' => 'Order #{order_number} Shipped', 'id' => 'Pesanan #{order_number} Dikirim'],
                'body' => [
                    'en' => '<h2>Your order is on its way!</h2><p>Dear {customer_name},</p><p>Your order <strong>#{order_number}</strong> has been shipped.</p>',
                    'id' => '<h2>Pesanan Anda sedang dalam perjalanan!</h2><p>Yth {customer_name},</p><p>Pesanan Anda <strong>#{order_number}</strong> telah dikirim.</p>',
                ],
                'variables' => ['order_number', 'customer_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Status Changed',
                'slug' => 'order_status_changed',
                'subject' => ['en' => 'Order #{order_number} Status Update', 'id' => 'Pembaruan Status Pesanan #{order_number}'],
                'body' => [
                    'en' => '<p>Dear {customer_name},</p><p>Your order <strong>#{order_number}</strong> status has been updated to <strong>{new_status}</strong>.</p>',
                    'id' => '<p>Yth {customer_name},</p><p>Status pesanan Anda <strong>#{order_number}</strong> telah diperbarui menjadi <strong>{new_status}</strong>.</p>',
                ],
                'variables' => ['order_number', 'customer_name', 'old_status', 'new_status'],
                'is_active' => true,
            ],
            [
                'name' => 'Low Stock Alert',
                'slug' => 'low_stock_alert',
                'subject' => ['en' => 'Low Stock Alert: {product_name}', 'id' => 'Peringatan Stok Rendah: {product_name}'],
                'body' => [
                    'en' => '<p>The following product variant is running low on stock:</p><p><strong>Product:</strong> {product_name}</p><p><strong>Variant:</strong> {variant_name}</p><p><strong>Current Stock:</strong> {current_stock}</p><p><strong>Threshold:</strong> {threshold}</p>',
                    'id' => '<p>Varian produk berikut stoknya hampir habis:</p><p><strong>Produk:</strong> {product_name}</p><p><strong>Varian:</strong> {variant_name}</p><p><strong>Stok Saat Ini:</strong> {current_stock}</p><p><strong>Batas:</strong> {threshold}</p>',
                ],
                'variables' => ['product_name', 'variant_name', 'current_stock', 'threshold'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
}
