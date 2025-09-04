@extends('layouts.app')

@section('page-title', 'Stock Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Stock Report</h1>
            <p class="mb-0">An overview of your current stock levels and sales performance.</p>
        </div>
        <div>
            <a href="{{ route('report.closed') }}" class="btn btn-info shadow-sm me-2">
                <i class="fas fa-eye fa-sm text-white-50"></i> View Closed Sales
            </a>
            <form method="POST" action="{{ route('transactions.closeSales') }}" class="d-inline" onsubmit="return confirm('Are you sure you want to close today\'s sales? This action cannot be undone.')">
                @csrf
                <button type="submit" class="btn btn-danger shadow-sm">
                    <i class="fas fa-lock fa-sm text-white-50"></i> Close Today's Sales
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Stock Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">TSH {{ number_format($total_stock_value, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Discounts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">TSH {{ number_format($total_discount, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Profit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">TSH {{ number_format($total_profit, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Table -->
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detailed Stock Report</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>In Stock</th>
                            <th>Sold</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Stock Value</th>
                            <th>Discount</th>
                            <th>Profit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p['name'] }}</td>
                            <td>{{ $p['quantity'] }}</td>
                            <td>{{ $p['total_sold'] }}</td>
                            <td>{{ number_format($p['cost_price'], 2) }}</td>
                            <td>{{ number_format($p['selling_price'], 2) }}</td>
                            <td>{{ number_format($p['cost_price'] * $p['quantity'], 2) }}</td>
                            <td>{{ number_format($p['discount'], 2) }}</td>
                            <td>{{ number_format($p['profit'], 2) }}</td>
                            <td>
                                @if($p['is_closed'])
                                    <span class="badge bg-success-soft text-success">Closed</span>
                                @else
                                    <span class="badge bg-warning-soft text-warning">Open</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">No data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="7" class="text-end">Totals:</th>
                            <th>{{ number_format($total_discount, 2) }}</th>
                            <th>{{ number_format($total_profit, 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .text-xs { font-size: 0.7rem; }
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1); color: #1cc88a !important; }
    .bg-warning-soft { background-color: rgba(246, 194, 62, 0.1); color: #f6c23e !important; }
</style>
@endsection