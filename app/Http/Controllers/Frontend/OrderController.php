<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return Inertia::render('Frontend/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order, Request $request): Response
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load('items.product.media');

        return Inertia::render('Frontend/Orders/Show', [
            'order' => $order,
        ]);
    }
}
