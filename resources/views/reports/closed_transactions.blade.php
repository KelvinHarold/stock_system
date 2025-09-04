@extends('layouts.app')

@section('page-title', 'Closed Sales Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Closed Sales</h1>
            <p class="mb-0">A detailed report of all closed sales transactions.</p>
        </div>
        <div>
            <a href="{{ route('reports.stock') }}" class="btn btn-secondary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Stock Report
            </a>
            <button onclick="window.print()" class="btn btn-primary shadow-sm">
                <i class="fas fa-print fa-sm text-white-50"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Closed Transactions Table -->
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Closed Sales Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Profit</th>
                            <th>Closed At</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($closedTransactions as $index => $t)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $t->product->name ?? 'N/A' }}</td>
                            <td>{{ $t->quantity }}</td>
                            <td>{{ number_format($t->unit_cost, 2) }}</td>
                            <td>{{ number_format($t->unit_price, 2) }}</td>
                            <td>{{ number_format($t->discount, 2) }}</td>
                            <td>{{ number_format(($t->unit_price - $t->unit_cost) * $t->quantity - $t->discount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->closed_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-success-soft text-success">Closed</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-info-circle me-1"></i> No closed transactions found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1); color: #1cc88a !important; }
</style>
@endsection