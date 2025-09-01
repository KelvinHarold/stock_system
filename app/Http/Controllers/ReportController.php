<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;

class ReportController extends Controller
{
    // ----------------- STOCK REPORT -----------------
    public function stockReport()
    {
        $products = Product::all();

        // Total stock value (current stock * cost price)
        $total_stock_value = $products->sum(function ($p) {
            return $p->cost_price * $p->quantity;
        });

        // Prepare per-product sold quantity and profit
        $products = $products->map(function ($p) {
            $soldTransactions = $p->transactions()->where('type','out')->get();
            $totalSoldQty = $soldTransactions->sum('quantity');
            $profit = $soldTransactions->sum(function ($t) {
                return ($t->unit_price - $t->unit_cost) * $t->quantity;
            });

            return [
                'name' => $p->name,
                'quantity' => $p->quantity,
                'cost_price' => $p->cost_price,
                'selling_price' => $p->selling_price,
                'total_sold' => $totalSoldQty,
                'profit' => $profit,
            ];
        });

        $total_profit = $products->sum('profit');

        return view('reports.stock', compact('products', 'total_stock_value', 'total_profit'));
    }

    // ----------------- CREATE SALE -----------------
    public function createSale()
    {
        $products = Product::orderBy('name')->get();
        return view('transactions.sales.create', compact('products'));
    }

    public function storeSale(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->quantity < $data['quantity']) {
            return back()->withInput()->withErrors(['quantity' => 'Not enough stock for this product.']);
        }

        $data['unit_price'] = $data['unit_price'] ?? $product->selling_price;
        $data['unit_cost'] = $product->cost_price;
        $data['type'] = StockTransaction::TYPE_OUT;
        $data['is_closed'] = false;

        StockTransaction::create($data);

        $product->quantity -= $data['quantity'];
        $product->save();

        return redirect()->route('transactions.index')->with('success', 'Sale recorded.');
    }

    // ----------------- CREATE PURCHASE -----------------
    public function createPurchase()
    {
        $products = Product::orderBy('name')->get();
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

        $data['unit_cost'] = $data['unit_cost'] ?? $product->cost_price;
        $data['unit_price'] = $product->selling_price;
        $data['type'] = StockTransaction::TYPE_IN;
        $data['is_closed'] = false;

        StockTransaction::create($data);

        $product->quantity += $data['quantity'];
        $product->save();

        return redirect()->route('transactions.index')->with('success', 'Purchase recorded.');
    }

    // ----------------- CLOSE SALES -----------------
    public function closeSales()
    {
        $products = Product::with(['transactions' => function ($q) {
            $q->where('is_closed', false)->orderBy('created_at');
        }])->get();

        $summary = [];

        foreach ($products as $product) {
            $soldTransactions = $product->transactions->where('type', StockTransaction::TYPE_OUT);
            $totalSoldQty = $soldTransactions->sum('quantity');
            $profit = $soldTransactions->sum(function ($tx) {
                return ($tx->unit_price - $tx->unit_cost) * $tx->quantity;
            });

            $summary[] = [
                'product' => $product,
                'total_sold' => $totalSoldQty,
                'remaining_stock' => $product->quantity,
                'profit' => $profit,
            ];
        }

        return view('transactions.close_sales', compact('summary'));
    }

    // ----------------- RESET DAILY SALES -----------------
    public function resetDailySales()
    {
        StockTransaction::where('is_closed', false)->update(['is_closed' => true]);

        return redirect()->route('transactions.index')
            ->with('success', 'Daily sales closed and archived successfully!');
    }

    // ----------------- TRANSACTIONS LIST -----------------
    public function index()
    {
        $transactions = StockTransaction::with('product')->latest()->paginate(30);
        return view('transactions.index', compact('transactions'));
    }

    public function show(StockTransaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }
}
