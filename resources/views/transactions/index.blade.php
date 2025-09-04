@extends('layouts.app')

@section('page-title', 'Transaction History')

@section('content')
<div class="container-fluid">

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Stock In Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Stock In (Purchases)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactions->where('type', 'in')->count() }} transactions</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stock Out Card -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-danger shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stock Out (Sales)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactions->where('type', 'out')->count() }} transactions</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Transactions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Notes</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction->id }}</td>
                            <td>
                                <a href="{{ route('products.show', $transaction->product) }}" class="text-dark fw-bold text-decoration-none">
                                    {{ $transaction->product->name }}
                                </a>
                            </td>
                            <td>{{ $transaction->quantity }}</td>
                            <td>TSH {{ number_format($transaction->unit_price, 2) }}</td>
                            <td>TSH {{ number_format($transaction->discount, 2) }}</td>
                            <td>{{ $transaction->notes ?? 'N/A' }}</td>
                            <td>
                                @if($transaction->type == 'in')
                                    <span class="badge bg-success-soft text-success">Stock In</span>
                                @else
                                    <span class="badge bg-danger-soft text-danger">Stock Out</span>
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-info-circle me-1"></i> No transactions found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="mt-3 d-flex justify-content-end">
                    <nav>
                        <ul class="pagination pagination-sm">
                            {{-- Previous Page Link --}}
                            @if ($transactions->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $transactions->previousPageUrl() }}">&laquo;</a></li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                @if ($page == $transactions->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($transactions->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $transactions->nextPageUrl() }}">&raquo;</a></li>
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
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
    .text-xs { font-size: 0.7rem; }
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1); color: #1cc88a !important; }
    .bg-danger-soft { background-color: rgba(231, 74, 59, 0.1); color: #e74a3b !important; }

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
