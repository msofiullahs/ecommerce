<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class ReportService
{
    public function salesReport(Carbon $from, Carbon $to): array
    {
        $orders = Order::whereBetween('created_at', [$from, $to->endOfDay()])
            ->where('payment_status', 'paid')
            ->get();

        return [
            'total_revenue' => $orders->sum('total'),
            'total_orders' => $orders->count(),
            'average_order_value' => $orders->count() > 0 ? round($orders->avg('total'), 2) : 0,
            'total_discount' => $orders->sum('discount_amount'),
            'daily_breakdown' => $orders->groupBy(fn ($o) => $o->created_at->format('Y-m-d'))
                ->map(fn ($group) => [
                    'date' => $group->first()->created_at->format('Y-m-d'),
                    'orders' => $group->count(),
                    'revenue' => round($group->sum('total'), 2),
                ])->values(),
            'payment_methods' => $orders->groupBy('payment_method')
                ->map(fn ($group) => [
                    'method' => $group->first()->payment_method ?? 'Unknown',
                    'count' => $group->count(),
                    'total' => round($group->sum('total'), 2),
                ])->values(),
        ];
    }

    public function productReport(Carbon $from, Carbon $to): array
    {
        return OrderItem::whereHas('order', fn ($q) =>
            $q->whereBetween('created_at', [$from, $to->endOfDay()])
              ->where('payment_status', 'paid')
        )
            ->select('product_id', 'product_name')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(subtotal) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_revenue')
            ->get()
            ->toArray();
    }
}
