<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductReportExport;
use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    public function sales(Request $request): Response
    {
        $from = Carbon::parse($request->get('from', now()->startOfMonth()->format('Y-m-d')));
        $to = Carbon::parse($request->get('to', now()->format('Y-m-d')));

        return Inertia::render('Reports/Sales', [
            'report' => $this->reportService->salesReport($from, $to),
            'filters' => ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')],
        ]);
    }

    public function products(Request $request): Response
    {
        $from = Carbon::parse($request->get('from', now()->startOfMonth()->format('Y-m-d')));
        $to = Carbon::parse($request->get('to', now()->format('Y-m-d')));

        return Inertia::render('Reports/Products', [
            'report' => $this->reportService->productReport($from, $to),
            'filters' => ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')],
        ]);
    }

    public function exportSalesExcel(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        return Excel::download(new SalesReportExport($from, $to), 'sales-report.xlsx');
    }

    public function exportSalesPdf(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        $report = $this->reportService->salesReport($from, $to);
        $pdf = Pdf::loadView('reports.sales', compact('report', 'from', 'to'));
        return $pdf->download('sales-report.pdf');
    }

    public function exportProductsExcel(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        return Excel::download(new ProductReportExport($from, $to), 'product-report.xlsx');
    }
}
