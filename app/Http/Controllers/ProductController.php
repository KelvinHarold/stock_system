<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index(Request $request)
{
    $search = $request->input('search');

    $products = Product::query()
        ->when($search, function ($query, $search) {
            // Filter by name or SKU
            $query->where('name', 'like', "%{$search}%")
                  // Prioritize matches to the top
                  ->orderByRaw("CASE WHEN name LIKE ? THEN 0 ELSE 1 END, name ASC", ["%{$search}%"]);
        })
        ->orderBy('name') // fallback ordering if no search
        ->paginate(20)
        ->withQueryString(); // preserve search in pagination links

    return view('products.index', compact('products', 'search'));
}


    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'initial_stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product = Product::create([
            'name' => $data['name'],
            'sku' => $data['sku'] ?? null,
            'cost_price' => $data['cost_price'],
            'selling_price' => $data['selling_price'],
            'quantity' => 0,
            'description' => $data['description'] ?? null,
        ]);

        // if initial stock supplied, create an 'in' transaction
        if (!empty($data['initial_stock']) && $data['initial_stock'] > 0) {
            StockTransaction::create([
                'product_id' => $product->id,
                'type' => StockTransaction::TYPE_IN,
                'quantity' => $data['initial_stock'],
                'unit_cost' => $product->cost_price,
                'unit_price' => null,
                'notes' => 'Initial stock on product creation',
            ]);

            $product->quantity = $data['initial_stock'];
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('deleted', 'Product deleted.');
    }

    public function show(Product $product)
    {
        $transactions = $product->transactions()->latest()->paginate(20);
        return view('products.show', compact('product','transactions'));
    }

    
}
