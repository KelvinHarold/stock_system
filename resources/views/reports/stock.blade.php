@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h1 class="h3">
        <i class="fas fa-boxes-stacked text-primary"></i> Stock Report
    </h1>
    <button onclick="window.print()" class="btn btn-success">
        <i class="fas fa-print text-primary"></i> Print Report
    </button>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <h5><i class="fas fa-chart-pie text-primary"></i> Summary</h5>
        <p><strong><i class="fas fa-cubes text-primary"></i> Total Products:</strong> {{ $products->count() }}</p>
        <p><strong><i class="fas fa-money-bill-wave text-primary"></i> Total Stock Value:</strong> {{ number_format($total_stock_value,2) }}</p>
        <p><strong><i class="fas fa-chart-line text-primary"></i> Total Potential Profit:</strong> {{ number_format($total_profit,2) }}</p>
    </div>
</div>

<div class="table-responsive shadow-sm">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-hashtag text-primary"></i> SN</th>
                <th><i class="fas fa-tag text-primary"></i> Name</th>
                <th><i class="fas fa-sort-numeric-up text-primary"></i> Quantity</th>
                <th><i class="fas fa-coins text-primary"></i> Cost Price</th>
                <th><i class="fas fa-dollar-sign text-primary"></i> Selling Price</th>
                <th><i class="fas fa-wallet text-primary"></i> Stock Value</th>
                <th><i class="fas fa-chart-line text-primary"></i> Potential Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p['name'] }}</td>
                <td>{{ $p['quantity'] }}</td>
                <td>{{ number_format($p['cost_price'], 2) }}</td>
                <td>{{ number_format($p['selling_price'], 2) }}</td>
                <td>{{ number_format($p['cost_price'] * $p['quantity'], 2) }}</td>
                <td>{{ number_format(($p['selling_price'] - $p['cost_price']) * $p['quantity'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Print styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .table, .table * {
            visibility: visible;
        }
        .card, .table {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        button {
            display: none;
        }
    }
</style>

@endsection
