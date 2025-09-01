@extends('layouts.app')
@section('content')
@include('components.success-message')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="fas fa-shopping-cart text-primary"></i> New Stock Out</h1>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left text-primary"></i> Back to Transactions
    </a>
</div>

<div class="row">
    @foreach($products as $p)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <!-- Product Image -->
                <div class="position-relative">
                    @if($p->image)
                        <img src="{{ asset('storage/' . $p->image) }}" 
                             class="card-img-top rounded-top" 
                             alt="{{ $p->name }}" 
                             style="height:200px; object-fit:cover;">
                    @else
                        <img src="https://via.placeholder.com/400x200?text=No+Image" 
                             class="card-img-top rounded-top" 
                             alt="No Image" 
                             style="height:200px; object-fit:cover;">
                    @endif

                    <!-- Low Stock Badge -->
                    @if($p->quantity < 5)
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                            Low Stock
                        </span>
                    @endif
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-1">
                        <i class="fas fa-box text-primary"></i> {{ $p->name }}
                    </h5>

                    <!-- Stock -->
                    <p class="mb-1 text-muted">
                        <strong>Stock:</strong> {{ $p->quantity }}
                    </p>

                    <!-- Selling Price -->
                    <p class="fw-bold text-success mb-3">
                        <i class="fas fa-tags"></i> Selling Price: 
                        {{ number_format($p->selling_price, 2) }} TZS
                    </p>

                    <!-- Sale Button -->
                    <button class="btn btn-primary mt-auto" data-bs-toggle="modal"
                            data-bs-target="#saleModal{{ $p->id }}">
                        <i class="fas fa-cash-register"></i> Sell
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="saleModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="{{ route('sales.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-cash-register text-primary"></i> Sell {{ $p->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Quantity -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-sort-numeric-up text-primary"></i> Quantity
                                </label>
                                <input type="number" name="quantity" class="form-control" 
                                       value="1" min="1" max="{{ $p->quantity }}" required>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-sticky-note text-primary"></i> Notes
                                </label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success">
                                <i class="fas fa-save"></i> Save Sale
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
