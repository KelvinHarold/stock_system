@extends('layouts.app')
@section('content')
 @include('components.success-message')
 
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">
        <i class="fas fa-exchange-alt text-primary"></i> Transactions
    </h1>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle text-white"></i> New Transaction
    </a>
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

<!-- Pagination -->
<div class="mt-3">
    {{ $transactions->links() }}
</div>

@endsection
