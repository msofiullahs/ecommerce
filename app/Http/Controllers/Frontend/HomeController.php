<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\SettingService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(SettingService $settings): Response
    {
        return Inertia::render('Frontend/Home', [
            'featuredProducts' => Product::active()->featured()
                ->with(['media', 'variants', 'category'])
                ->take(8)
                ->get(),
            'categories' => Category::active()
                ->withCount('products')
                ->orderBy('sort_order')
                ->get(),
            'banners' => $settings->get('hero_banners', []),
            'siteName' => $settings->get('site_name'),
        ]);
    }
}
