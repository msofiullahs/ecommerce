<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $today = now();
        $thirtyDaysAgo = now()->subDays(30);

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_revenue_today' => Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total'),
                'total_orders_today' => Order::whereDate('created_at', $today)->count(),
                'total_revenue_month' => Order::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->where('payment_status', 'paid')->sum('total'),
                'total_orders_month' => Order::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->count(),
                'total_customers' => User::role('customer')->count(),
                'total_products' => Product::where('is_active', true)->count(),
                'low_stock_count' => ProductVariant::whereColumn('stock', '<=', 'low_stock_threshold')->where('is_active', true)->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
            ],
            'revenueChart' => Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$thirtyDaysAgo, $today])
                ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'topProducts' => OrderItem::whereHas('order', fn ($q) =>
                $q->whereBetween('created_at', [$thirtyDaysAgo, $today])->where('payment_status', 'paid')
            )
                ->select('product_id', 'product_name')
                ->selectRaw('SUM(quantity) as total_sold, SUM(subtotal) as total_revenue')
                ->groupBy('product_id', 'product_name')
                ->orderByDesc('total_revenue')
                ->take(5)
                ->get(),
            'recentOrders' => Order::with('user')->latest()->take(10)->get(),
            'ordersByStatus' => Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->get(),
        ]);
    }
}
