<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Carbon $from, private Carbon $to) {}

    public function collection(): Collection
    {
        return Order::whereBetween('created_at', [$this->from, $this->to->endOfDay()])
            ->where('payment_status', 'paid')
            ->with('user')
            ->get();
    }

    public function headings(): array
    {
        return ['Order #', 'Date', 'Customer', 'Subtotal', 'Discount', 'Total', 'Status', 'Payment Method'];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_at->format('Y-m-d H:i'),
            $order->user?->name ?? 'Guest',
            $order->subtotal,
            $order->discount_amount,
            $order->total,
            $order->status->value ?? $order->status,
            $order->payment_method,
        ];
    }
}
