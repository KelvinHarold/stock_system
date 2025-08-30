@extends('layouts.app')
@section('content')


<!-- Filter Section -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form class="row g-3 align-items-center" method="get">
            <div class="col-auto">
                <label class="form-label">Start Date</label>
                <input type="date" name="start" class="form-control" 
                       value="{{ request('start', $start->toDateString()) }}">
            </div>
            <div class="col-auto">
                <label class="form-label">End Date</label>
                <input type="date" name="end" class="form-control" 
                       value="{{ request('end', $end->toDateString()) }}">
            </div>
            <div class="col-auto align-self-end">
                <button class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h2 class="card-text">{{ $total_products }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title">Stock Value (Cost)</h5>
                <h2 class="card-text">{{ number_format($stock_value,2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title">Profit (Selected Range)</h5>
                <h2 class="card-text">{{ number_format($profit,2) }}</h2>
                <p class="mb-0">
                    <span class="badge bg-light text-dark">Revenue: {{ number_format($revenue,2) }}</span>
                    <span class="badge bg-light text-dark">COGS: {{ number_format($cogs,2) }}</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stock vs Profit Chart -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Stock vs Profit per Product</h5>
        <canvas id="stockProfitChart" height="150"></canvas>
    </div>
</div>

<!-- Low Stock Table -->
<h4 class="mb-3">Low Stock Items <span class="badge bg-danger">{{ $low_stock->count() }}</span></h4>
<div class="table-responsive shadow-sm">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Qty</th>
                <th>Cost</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($low_stock as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>
                    @if($p->quantity <= 0)
                        <span class="badge bg-danger">{{ $p->quantity }}</span>
                    @elseif($p->quantity == 1)
                        <span class="badge bg-warning text-dark">{{ $p->quantity }}</span>
                    @else
                        {{ $p->quantity }}
                    @endif
                </td>
                <td>{{ number_format($p->cost_price,2) }}</td>
                <td>
                    <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">No low stock items</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('stockProfitChart').getContext('2d');
const stockProfitChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [
            {
                label: 'Stock Value',
                data: @json($stockData),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Profit',
                data: @json($profitData),
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Stock Value vs Profit per Product' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

@endsection
