@extends('layouts.app')
@section('content')
@include('components.success-message')

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title"><i class="fas fa-arrow-down"></i> Stock In</h5>
                    <h3 class="card-text">{{ $transactions->where('type', 'in')->count() }}</h3>
                </div>
                <i class="fas fa-box fa-2x"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title"><i class="fas fa-arrow-up"></i> Stock Out</h5>
                    <h3 class="card-text">{{ $transactions->where('type', 'out')->count() }}</h3>
                </div>
                <i class="fas fa-box fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">
        <i class="fas fa-exchange-alt text-primary"></i> Transactions
    </h1>
</div>

<div class="table-responsive shadow-sm">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-box text-primary"></i> Product</th>
                <th><i class="fas fa-random text-primary"></i> Type</th>
                <th><i class="fas fa-sort-numeric-up text-primary"></i> Qty</th>
                <th><i class="fas fa-coins text-primary"></i> Unit Cost</th>
                <th><i class="fas fa-dollar-sign text-primary"></i> Unit Price</th>
                <th><i class="fas fa-calendar-alt text-primary"></i> Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>{{ $t->product->name ?? '—' }}</td>
                <td>
                    @if($t->type === 'in')
                        <span class="badge bg-success">
                            <i class="fas fa-arrow-down"></i> Stock In
                        </span>
                    @else
                        <span class="badge bg-danger">
                            <i class="fas fa-arrow-up"></i> Stock Out
                        </span>
                    @endif
                </td>
                <td>{{ $t->quantity }}</td>
                <td>{{ number_format($t->unit_cost,2) }}</td>
                <td>{{ number_format($t->unit_price,2) }}</td>
                <td>{{ $t->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    <i class="fas fa-info-circle text-primary"></i> No transactions found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $transactions->links() }}
</div>

@endsection
