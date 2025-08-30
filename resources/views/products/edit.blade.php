@extends('layouts.app')
@section('content')
 @include('components.success-message')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-edit text-primary"></i> Edit Product
    </h1>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left text-primary"></i> Back to Products
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="{{ route('products.update', $product) }}">
            @csrf 
            @method('PUT')

            <!-- Name & SKU -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tag text-primary"></i> Name
                    </label>
                    <input name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>
            
            </div>

            <!-- Prices -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign text-primary"></i> Cost Price
                    </label>
                    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tags text-primary"></i> Selling Price
                    </label>
                    <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}" required>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-align-left text-primary"></i> Description
                </label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Submit -->
            <div class="d-flex justify-content-end">
                <button class="btn btn-success">
                    <i class="fas fa-save text-white"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
