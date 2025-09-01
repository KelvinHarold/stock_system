@extends('layouts.app')
@section('content')

@include('components.success-message')
<!-- Header & New Product Button -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">
        <i class="fas fa-boxes text-primary"></i> Products
    </h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle text-white"></i> New Product
    </a>
</div>

<!-- Search Box -->
<form method="GET" action="{{ route('products.index') }}" class="mb-3 d-flex">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Search by name or SKU">
    <button type="submit" class="btn btn-outline-primary">
        <i class="fas fa-search text-primary"></i> Search
    </button>
</form>

<!-- Products Table -->
<div class="table-responsive shadow-sm">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-image text-primary"></i> Image</th>
                <th><i class="fas fa-tag text-primary"></i> Name</th>
                <th><i class="fas fa-cubes text-primary"></i> Qty</th>
                <th><i class="fas fa-dollar-sign text-primary"></i> Cost</th>
                <th><i class="fas fa-tags text-primary"></i> Price</th>
                <th><i class="fas fa-tools text-primary"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
            <tr>
                <!-- Product Image -->
                <td>
                    @if($p->image)
                        <img src="{{ asset('storage/' . $p->image) }}" 
                             alt="{{ $p->name }}" 
                             class="img-thumbnail" 
                             style="width:60px; height:60px; object-fit:cover;">
                    @else
                        <img src="{{ asset('images/default.png') }}" 
                             alt="Default" 
                             class="img-thumbnail" 
                             style="width:60px; height:60px; object-fit:cover;">
                    @endif
                </td>

                <!-- Product Name -->
                <td>
                    <a href="{{ route('products.show', $p) }}" class="fw-bold">
                        <i class="fas fa-box text-primary"></i> 
                        {!! $search ? preg_replace("/($search)/i", '<mark>$1</mark>', $p->name) : $p->name !!}
                    </a>
                </td>

                <!-- Quantity -->
                <td>
                    @if($p->quantity <= 0)
                        <span class="badge bg-danger">{{ $p->quantity }}</span>
                    @elseif($p->quantity <= 5)
                        <span class="badge bg-warning text-dark">{{ $p->quantity }}</span>
                    @else
                        <span class="badge bg-success">{{ $p->quantity }}</span>
                    @endif
                </td>

                <!-- Cost & Price -->
                <td>{{ number_format($p->cost_price,2) }}</td>
                <td>{{ number_format($p->selling_price,2) }}</td>

                <!-- Actions -->
                <td>
                    <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit text-primary"></i> Edit
                    </a>
                    <form action="{{ route('products.destroy', $p) }}" method="post" style="display:inline-block" 
                          onsubmit="return confirm('Delete product?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash text-white"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">
                    <i class="fas fa-info-circle text-primary"></i> No products found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-3">
    {{ $products->links() }}
</div>

@endsection
