<?php

namespace App\Exports;

use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductReportExport implements FromCollection, WithHeadings
{
    public function __construct(private Carbon $from, private Carbon $to) {}

    public function collection(): Collection
    {
        return OrderItem::whereHas('order', fn ($q) =>
            $q->whereBetween('created_at', [$this->from, $this->to->endOfDay()])
              ->where('payment_status', 'paid')
        )
            ->select('product_id', 'product_name')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(subtotal) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_revenue')
            ->get();
    }

    public function headings(): array
    {
        return ['Product ID', 'Product Name', 'Total Quantity Sold', 'Total Revenue'];
    }
}
