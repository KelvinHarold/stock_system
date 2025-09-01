@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h1 class="h3">
        <i class="fas fa-boxes-stacked text-primary"></i> Stock Report
    </h1>
    <div>
        <!-- Print Button -->
        <button onclick="window.print()" class="btn btn-success me-2">
            <i class="fas fa-print text-primary"></i> Print Report
        </button>

        <!-- Close Sales Button -->
        <a href="{{ route('transactions.close_sales') }}" class="btn btn-primary me-2">
            <i class="fas fa-check-circle"></i> Close Sales
        </a>

        <!-- Reset Daily Sales Button -->
        <form method="POST" action="{{ route('transactions.reset_daily_sales') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-redo-alt"></i> Reset Daily Sales
            </button>
        </form>
    </div>
</div>

<!-- Summary Card -->
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <h5><i class="fas fa-chart-pie text-primary"></i> Summary</h5>
        <p><strong><i class="fas fa-cubes text-primary"></i> Total Products:</strong> {{ $products->count() }}</p>
        <p><strong><i class="fas fa-money-bill-wave text-primary"></i> Total Stock Value:</strong> {{ number_format($total_stock_value,2) }}</p>
        <p><strong><i class="fas fa-chart-line text-primary"></i> Total Potential Profit:</strong> {{ number_format($total_profit,2) }}</p>
    </div>
</div>

<!-- Stock Table -->
<div class="table-responsive shadow-sm">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-hashtag text-primary"></i> SN</th>
                <th><i class="fas fa-tag text-primary"></i> Name</th>
                <th><i class="fas fa-sort-numeric-up text-primary"></i> Quantity In Stock</th>
                <th><i class="fas fa-sort-numeric-up text-success"></i> Quantity Sold</th>
                <th><i class="fas fa-coins text-primary"></i> Cost Price</th>
                <th><i class="fas fa-dollar-sign text-primary"></i> Selling Price</th>
                <th><i class="fas fa-wallet text-primary"></i> Stock Value</th>
                <th><i class="fas fa-chart-line text-primary"></i> Profit</th>
            </tr>
        </thead>
        <tbody>
            @php $totalProfit = 0; @endphp
            @foreach($products as $index => $p)
            @php
                $soldQty = $p['total_sold'] ?? 0;
                $profit = ($p['selling_price'] - $p['cost_price']) * $soldQty;
                $totalProfit += $profit;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p['name'] }}</td>
                <td>{{ $p['quantity'] }}</td>
                <td>{{ $soldQty }}</td>
                <td>{{ number_format($p['cost_price'], 2) }}</td>
                <td>{{ number_format($p['selling_price'], 2) }}</td>
                <td>{{ number_format($p['cost_price'] * $p['quantity'], 2) }}</td>
                <td>{{ number_format($profit, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-secondary">
                <th colspan="7" class="text-end">Total Profit:</th>
                <th>{{ number_format($totalProfit, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Print styles -->
<style>
    @media print {
        body * { visibility: hidden; }
        .card, .table, .table * { visibility: visible; }
        .card, .table { position: absolute; left: 0; top: 0; width: 100%; }
        button, form { display: none; }
    }
</style>

@endsection
