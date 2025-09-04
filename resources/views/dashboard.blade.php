@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid content-animation">

    <!-- Filter Section -->
    <div class="card shadow-sm mb-4 fade-in">
        <div class="card-body">
            <form class="row g-3 align-items-center" method="get">
                <div class="col-md-5">
                    <label for="start" class="form-label">Start Date</label>
                    <input type="date" id="start" name="start" class="form-control" 
                           value="{{ request('start', $start->toDateString()) }}">
                </div>
                <div class="col-md-5">
                    <label for="end" class="form-label">End Date</label>
                    <input type="date" id="end" name="end" class="form-control" 
                           value="{{ request('end', $end->toDateString()) }}">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4 fade-in delay-1">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase small">Total Products</div>
                            <h4 class="font-weight-bold mb-0 count text-primary" data-count="{{ $total_products }}">0</h4>
                        </div>
                        <div class="icon-circle bg-primary-soft">
                            <i class="fas fa-boxes fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4 fade-in delay-2">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase small">Stock Value (Cost)</div>
                            <h4 class="font-weight-bold mb-0 count text-info" data-count="{{ $stock_value }}">0</h4>
                        </div>
                        <div class="icon-circle bg-info-soft">
                            <i class="fas fa-dollar-sign fa-lg text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4 fade-in delay-3">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase small">Profit (Range)</div>
                            <h4 class="font-weight-bold mb-0 count text-success" data-count="{{ $profit }}">0</h4>
                        </div>
                        <div class="icon-circle bg-success-soft">
                            <i class="fas fa-chart-line fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4 fade-in delay-4">
            <div class="card summary-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase small">Discounts (Range)</div>
                            <h4 class="font-weight-bold mb-0 count text-warning" data-count="{{ $discount }}">0</h4>
                        </div>
                        <div class="icon-circle bg-warning-soft">
                            <i class="fas fa-tags fa-lg text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Low Stock -->
    <div class="row fade-in delay-5">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sales Performance</h6>
                </div>
                <div class="card-body">
                    <canvas id="stockProfitChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Low Stock Items <span class="badge bg-danger rounded-pill">{{ $low_stock->count() }}</span></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <tbody>
                                @forelse($low_stock as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td>
                                        <span class="badge {{ $p->quantity <= 0 ? 'bg-danger' : 'bg-warning' }} text-dark">{{ $p->quantity }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td class="text-center">No low stock items.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Fast Count-up animation for large numbers
function animateValue(element, start, end, duration = 1500) {
    let range = end - start;
    let stepTime = 20; // update every 20ms
    let steps = duration / stepTime;
    let increment = range / steps;
    let current = start;
    let stepCount = 0;

    let timer = setInterval(function() {
        stepCount++;
        current = start + increment * stepCount;

        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }

        element.textContent = new Intl.NumberFormat().format(Math.floor(current));
    }, stepTime);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.count').forEach(el => {
        const end = parseInt(el.getAttribute('data-count'));
        animateValue(el, 0, end, 1500);
    });

    // Chart animation
    const ctx = document.getElementById('stockProfitChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                { label: 'Stock Value', data: @json($stockData), backgroundColor: 'rgba(78, 115, 223, 0.5)', borderColor: 'rgba(78, 115, 223, 1)', borderWidth: 1 },
                { label: 'Profit', data: @json($profitData), backgroundColor: 'rgba(28, 200, 138, 0.5)', borderColor: 'rgba(28, 200, 138, 1)', borderWidth: 1 },
                { label: 'Discount', data: @json($discountData), backgroundColor: 'rgba(246, 194, 62, 0.5)', borderColor: 'rgba(246, 194, 62, 1)', borderWidth: 1 }
            ]
        },
        options: {
            responsive: true,
            animation: { duration: 1500, easing: 'easeOutQuart' },
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>

<style>
/* Fade-in animations */
.fade-in { opacity: 0; transform: translateY(20px); animation: fadeSlideIn 0.8s forwards; }
.fade-in.delay-1 { animation-delay: 0.2s; }
.fade-in.delay-2 { animation-delay: 0.4s; }
.fade-in.delay-3 { animation-delay: 0.6s; }
.fade-in.delay-4 { animation-delay: 0.8s; }
.fade-in.delay-5 { animation-delay: 1s; }

@keyframes fadeSlideIn {
    to { opacity: 1; transform: translateY(0); }
}

/* Soft background colors */
.bg-primary-soft { background-color: rgba(78, 115, 223, 0.1); }
.bg-info-soft { background-color: rgba(54, 185, 204, 0.1); }
.bg-success-soft { background-color: rgba(28, 200, 138, 0.1); }
.bg-warning-soft { background-color: rgba(246, 194, 62, 0.1); }

/* Colored number text */
.text-primary { color: #4e73df !important; }
.text-info { color: #36b9cc !important; }
.text-success { color: #1cc88a !important; }
.text-warning { color: #f6c23e !important; }

/* Icon circle */
.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

@endsection
