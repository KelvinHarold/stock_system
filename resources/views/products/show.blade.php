@extends('layouts.app')
@section('content')
 @include('components.success-message')

<!-- Product Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-box text-primary"></i> {{ $product->name }}
    </h1>
    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-secondary">
        <i class="fas fa-edit text-primary"></i> Edit Product
    </a>
</div>

<!-- Product Info -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-info-circle text-primary"></i> Product Details
                </h5>
                <p><strong><i class="fas fa-cubes text-primary"></i> Quantity:</strong> 
                    @if($product->quantity <= 0)
                        <span class="badge bg-danger">{{ $product->quantity }}</span>
                    @elseif($product->quantity <= 5)
                        <span class="badge bg-warning text-dark">{{ $product->quantity }}</span>
                    @else
                        {{ $product->quantity }}
                    @endif
                </p>
                <p><strong><i class="fas fa-dollar-sign text-primary"></i> Cost:</strong> {{ number_format($product->cost_price,2) }}</p>
                <p><strong><i class="fas fa-tags text-primary"></i> Price:</strong> {{ number_format($product->selling_price,2) }}</p>
                @if($product->description)
                    <p><i class="fas fa-align-left text-primary"></i> {{ $product->description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Transactions Section -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="h5">
        <i class="fas fa-exchange-alt text-primary"></i> Transactions
    </h3>
</div>

<div class="table-responsive shadow-sm">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-random"></i> Type</th>
                <th><i class="fas fa-sort-numeric-up"></i> Quantity</th>
                <th><i class="fas fa-dollar-sign"></i> Unit Cost</th>
                <th><i class="fas fa-tags"></i> Unit Price</th>
                <th><i class="fas fa-calendar-alt"></i> Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>{{ ucfirst($t->type) }}</td>
                <td>{{ $t->quantity }}</td>
                <td>{{ number_format($t->unit_cost,2) }}</td>
                <td>{{ number_format($t->unit_price,2) }}</td>
                <td>{{ $t->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    <i class="fas fa-inbox"></i> No transactions yet
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
