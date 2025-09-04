<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function index()
    {
        $transactions = StockTransaction::with('product')->latest()->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function show(StockTransaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    // ----------------- SALES -----------------

    public function createSale()
    {
        $products = Product::orderBy('name')->paginate(8);
        return view('transactions.sales.create', compact('products'));
    }

   public function storeSale(Request $request)
{
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
        'unit_price' => 'nullable|numeric|min:0',
        'discount'   => 'nullable|numeric|min:0',
        'notes'      => 'nullable|string',
    ]);

    $product = Product::findOrFail($data['product_id']);

    // Ensure stock is available
    if ($product->quantity < $data['quantity']) {
        return back()->withInput()->withErrors(['quantity' => 'Not enough stock for this product.']);
    }

    // Set defaults
    $data['unit_price'] = $data['unit_price'] ?? $product->selling_price;
    $data['unit_cost']  = $product->cost_price;
    $data['type']       = StockTransaction::TYPE_OUT;
    $data['discount']   = $data['discount'] ?? 0;

    // Save transaction
    StockTransaction::create($data);

    // Update stock
    $product->quantity -= $data['quantity'];
    $product->save();

    

    return redirect()->route('transactions.index')->with('success', 'Sale recorded.');
}


    // ----------------- PURCHASES -----------------

  public function createPurchase()
{
    $products = Product::orderBy('name')->paginate(8); // paginate by 8
    return view('transactions.purchases.create', compact('products'));
}


    public function storePurchase(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'unit_cost'  => 'nullable|numeric|min:0',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($data['product_id']);

        if (!isset($data['unit_cost']) || $data['unit_cost'] === null) {
            $data['unit_cost'] = $product->cost_price;
        }
        $data['unit_price'] = $product->selling_price;
        $data['type'] = StockTransaction::TYPE_IN;

        // Save transaction
        StockTransaction::create($data);

        // Update stock
        $product->quantity += $data['quantity'];
        $product->save();

        return redirect()->route('transactions.index')->with('success', 'Purchase recorded.');
    }
}
