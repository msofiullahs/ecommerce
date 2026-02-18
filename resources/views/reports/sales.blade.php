<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; }
        .summary { margin-bottom: 20px; }
        .summary dt { font-weight: bold; display: inline; }
        .summary dd { display: inline; margin-left: 5px; margin-right: 20px; }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <p>Period: {{ $from->format('Y-m-d') }} to {{ $to->format('Y-m-d') }}</p>
    <dl class="summary">
        <dt>Total Revenue:</dt><dd>{{ number_format($report['total_revenue'], 2) }}</dd>
        <dt>Total Orders:</dt><dd>{{ $report['total_orders'] }}</dd>
        <dt>Average Order Value:</dt><dd>{{ number_format($report['average_order_value'], 2) }}</dd>
        <dt>Total Discounts:</dt><dd>{{ number_format($report['total_discount'], 2) }}</dd>
    </dl>
    @if(count($report['daily_breakdown']) > 0)
    <h2>Daily Breakdown</h2>
    <table>
        <thead><tr><th>Date</th><th>Orders</th><th>Revenue</th></tr></thead>
        <tbody>
        @foreach($report['daily_breakdown'] as $day)
            <tr><td>{{ $day['date'] }}</td><td>{{ $day['orders'] }}</td><td>{{ number_format($day['revenue'], 2) }}</td></tr>
        @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>
