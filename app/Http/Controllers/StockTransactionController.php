<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function index()
    {
        $transactions = StockTransaction::with('product')->latest()->paginate(30);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($data['product_id']);

        // determine defaults if not provided
        if ($data['type'] === StockTransaction::TYPE_IN) {
            if (!isset($data['unit_cost']) || $data['unit_cost'] === null) {
                $data['unit_cost'] = $product->cost_price;
            }
        } else { // out
            if (!isset($data['unit_price']) || $data['unit_price'] === null) {
                $data['unit_price'] = $product->selling_price;
            }
            if (!isset($data['unit_cost']) || $data['unit_cost'] === null) {
                $data['unit_cost'] = $product->cost_price;
            }
        }

        // prevent negative stock on 'out' transactions
        if ($data['type'] === StockTransaction::TYPE_OUT && $product->quantity < $data['quantity']) {
            return back()->withInput()->withErrors(['quantity' => 'Not enough stock for this product.']);
        }

        $tx = StockTransaction::create($data);

        // apply quantity change
        if ($data['type'] === StockTransaction::TYPE_IN) {
            $product->quantity += $data['quantity'];
        } else {
            $product->quantity -= $data['quantity'];
        }
        $product->save();

        return redirect()->route('transactions.index')->with('success', 'Transaction recorded.');
    }

    public function show(StockTransaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }
}
