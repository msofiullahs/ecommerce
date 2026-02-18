<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => ['en' => 'My Store', 'id' => 'Toko Saya'], 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => ['en' => 'Your one-stop shop', 'id' => 'Belanja mudah dan nyaman'], 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_logo', 'value' => null, 'type' => 'image'],
            ['group' => 'general', 'key' => 'site_favicon', 'value' => null, 'type' => 'image'],
            ['group' => 'general', 'key' => 'currency', 'value' => 'IDR', 'type' => 'text'],
            ['group' => 'general', 'key' => 'currency_symbol', 'value' => 'Rp', 'type' => 'text'],

            // SEO
            ['group' => 'seo', 'key' => 'meta_title', 'value' => ['en' => 'My Store - Online Shopping', 'id' => 'Toko Saya - Belanja Online'], 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => ['en' => 'Shop the best products online', 'id' => 'Belanja produk terbaik secara online'], 'type' => 'textarea'],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => '', 'type' => 'text'],

            // Social
            ['group' => 'social', 'key' => 'facebook_url', 'value' => '', 'type' => 'text'],
            ['group' => 'social', 'key' => 'instagram_url', 'value' => '', 'type' => 'text'],
            ['group' => 'social', 'key' => 'twitter_url', 'value' => '', 'type' => 'text'],
            ['group' => 'social', 'key' => 'whatsapp_number', 'value' => '', 'type' => 'text'],

            // Appearance
            ['group' => 'appearance', 'key' => 'hero_banners', 'value' => [], 'type' => 'json'],
            ['group' => 'appearance', 'key' => 'footer_text', 'value' => ['en' => 'All rights reserved.', 'id' => 'Hak cipta dilindungi.'], 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
