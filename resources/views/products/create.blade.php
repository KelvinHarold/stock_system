@extends('layouts.app')

@section('page-title', 'Create New Product')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create New Product</h1>
            <p class="mb-0">Add a new product to your inventory.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Products
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Product Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                <div class="row">
                    <!-- Cost Price -->
                    <div class="col-md-6 mb-3">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <div class="input-group">
                            <span class="input-group-text">KES</span>
                            <input type="number" step="0.01" id="cost_price" name="cost_price" class="form-control" value="{{ old('cost_price', 0) }}" required>
                        </div>
                    </div>

                    <!-- Selling Price -->
                    <div class="col-md-6 mb-3">
                        <label for="selling_price" class="form-label">Selling Price</label>
                        <div class="input-group">
                            <span class="input-group-text">KES</span>
                            <input type="number" step="0.01" id="selling_price" name="selling_price" class="form-control" value="{{ old('selling_price', 0) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Initial Stock -->
                <div class="mb-3">
                    <label for="initial_stock" class="form-label">Initial Stock</label>
                    <input type="number" id="initial_stock" name="initial_stock" class="form-control" value="{{ old('initial_stock', 0) }}">
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>

                <!-- Product Image -->
                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save fa-sm text-white-50"></i> Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection