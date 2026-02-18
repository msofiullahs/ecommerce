<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(SettingService $settings): JsonResponse
    {
        return response()->json([
            'data' => [
                'site_name' => $settings->get('site_name'),
                'currency' => $settings->get('currency', 'IDR'),
                'currency_symbol' => $settings->get('currency_symbol', 'Rp'),
                'banners' => $settings->get('hero_banners', []),
                'social' => [
                    'facebook' => $settings->get('facebook_url'),
                    'instagram' => $settings->get('instagram_url'),
                    'twitter' => $settings->get('twitter_url'),
                    'whatsapp' => $settings->get('whatsapp_number'),
                ],
            ],
        ]);
    }
}
