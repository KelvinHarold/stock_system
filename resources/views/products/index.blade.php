@extends('layouts.app')

@section('page-title', 'Manage Products')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Products</h1>
            <p class="mb-0">Manage your product inventory.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Product
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="d-flex flex-wrap gap-2">
                <div class="flex-grow-1">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or SKU...">
                </div>
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Stock</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                        <tr>
                            <!-- Product Image -->
                            <td>
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" style="width:50px; height:50px; object-fit:cover; border-radius: 0.25rem;">
                                @else
                                    <img src="{{ asset('images/default.png') }}" alt="Default" style="width:50px; height:50px; object-fit:cover; border-radius: 0.25rem;">
                                @endif
                            </td>

                            <!-- Product Name -->
                            <td>
                                <a href="{{ route('products.show', $p) }}" class="fw-bold text-dark text-decoration-none">
                                    {!! request('search') ? preg_replace("/(" . request('search') . ")/i", '<mark class="bg-warning-subtle">$1</mark>', $p->name) : $p->name !!}
                                </a>
                                <div class="small text-muted">{{ $p->sku }}</div>
                            </td>

                            <!-- Quantity -->
                            <td>
                                @if($p->quantity <= 0)
                                    <span class="badge bg-danger-soft text-danger">{{ $p->quantity }}</span>
                                @elseif($p->quantity <= 5)
                                    <span class="badge bg-warning-soft text-warning">{{ $p->quantity }}</span>
                                @else
                                    <span class="badge bg-success-soft text-success">{{ $p->quantity }}</span>
                                @endif
                            </td>

                            <!-- Cost & Price -->
                            <td>TSH {{ number_format($p->cost_price, 2) }}</td>
                            <td>TSH {{ number_format($p->selling_price, 2) }}</td>

                            <!-- Actions -->
                            <td>
                                <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-outline-info me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $p) }}" method="post" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-info-circle me-1"></i> No products found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="mt-3 d-flex justify-content-end">
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
    </div>
</div>

<style>
    .bg-danger-soft { background-color: rgba(244, 67, 54, 0.1); }
    .text-danger { color: #d32f2f !important; }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
    .text-warning { color: #ffa000 !important; }
    .bg-success-soft { background-color: rgba(76, 175, 80, 0.1); }
    .text-success { color: #388e3c !important; }

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
