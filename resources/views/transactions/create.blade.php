@extends('layouts.app')
@section('content')
 @include('components.success-message')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-plus-square text-primary"></i> New Transaction
    </h1>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left text-primary"></i> Back to Products
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="{{ route('transactions.store') }}">
            @csrf

            <!-- Product Selection -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-box text-primary"></i> Product
                </label>
                <select name="product_id" class="form-control" required>
                    <option value="">Select product</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} 
                            ({{ $p->quantity }} in stock)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Transaction Type -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-random text-primary"></i> Type
                </label>
                <select name="type" class="form-control" required>
                    <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>
                        Stock In
                    </option>
                    <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>
                        Stock Out / Sale
                    </option>
                </select>
            </div>

            <!-- Quantity -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-sort-numeric-up text-primary"></i> Quantity
                </label>
                <input type="number" name="quantity" class="form-control" 
                       value="{{ old('quantity',1) }}" min="1" required>
            </div>

            <!-- Notes -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-sticky-note text-primary"></i> Notes (optional)
                </label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit -->
            <div class="d-flex justify-content-end">
                <button class="btn btn-success">
                    <i class="fas fa-save text-white"></i> Save Transaction
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
