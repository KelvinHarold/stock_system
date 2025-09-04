@extends('layouts.app')

@section('page-title', 'Reduce Stock')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Reduce Stock / New Sale</h1>
            <p class="mb-0">Select a product to record a sale.</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Transactions
        </a>
    </div>

    <!-- Product Grid -->
    <div class="row">
        @forelse($products as $p)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm h-100 product-card">
                    <!-- Product Image -->
                    <div class="position-relative">
                        @if($p->image)
                            <img src="{{ asset('storage/' . $p->image) }}" class="card-img-top" alt="{{ $p->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default.png') }}" class="card-img-top" alt="No Image" style="height: 200px; object-fit: cover;">
                        @endif

                        <!-- Stock Badge -->
                        @if($p->quantity <= 5 && $p->quantity > 0)
                            <span class="badge bg-warning position-absolute top-0 end-0 m-2">Low Stock</span>
                        @elseif($p->quantity <= 0)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Out of Stock</span>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-dark fw-bold">{{ $p->name }}</h5>
                        
                        <p class="mb-1 text-muted">
                            <strong>Stock:</strong> {{ $p->quantity }} units
                        </p>

                        <p class="fw-bold text-primary mb-3">
                            Selling Price: KES {{ number_format($p->selling_price, 2) }}
                        </p>

                        <!-- Sale Button -->
                        <button class="btn btn-primary mt-auto" data-bs-toggle="modal"
                                data-bs-target="#saleModal{{ $p->id }}" {{ $p->quantity <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cash-register me-1"></i> Reduce Stock
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sale Modal -->
            <div class="modal fade" id="saleModal{{ $p->id }}" tabindex="-1" aria-labelledby="saleModalLabel{{ $p->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="post" action="{{ route('sales.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="saleModalLabel{{ $p->id }}">Sell {{ $p->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" 
                                           value="1" min="1" max="{{ $p->quantity }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="discount" class="form-label">Discount (KES)</label>
                                    <input type="number" id="discount" name="discount" class="form-control" 
                                           value="0" min="0" max="{{ $p->quantity * $p->selling_price }}">
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Save Sale
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-1"></i> No products available to sell.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination pagination-sm">
                {{-- Previous Page Link --}}
                @if ($products->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}">&laquo;</a></li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if ($page == $products->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($products->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}">&raquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
    @endif

</div>

<style>
    .product-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Pagination styling */
    .pagination .page-item .page-link {
        color: #4e73df;
        border-radius: 0.25rem;
        margin: 0 2px;
    }
    .pagination .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
        color: #fff;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    .pagination .page-item .page-link:hover {
        background-color: #e2e6ea;
    }
</style>
@endsection
