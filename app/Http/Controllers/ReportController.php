<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalesClosedSummary;

class ReportController extends Controller
{
    // ----------------- STOCK REPORT -----------------
// ----------------- STOCK REPORT -----------------
public function stockReport()
{
    // Fetch all products that have at least one 'out' transaction
    $products = Product::whereHas('transactions', function ($q) {
        $q->where('type', StockTransaction::TYPE_OUT);
    })->get();

    // Total stock value (for displayed products)
    $total_stock_value = $products->sum(function ($p) {
        return $p->quantity * $p->cost_price;
    });

    // Prepare per-product sold quantity, discount, and profit
    $products = $products->map(function ($p) {
        $soldTransactions = $p->transactions()
            ->where('type', StockTransaction::TYPE_OUT)
            ->get();

        $totalSoldQty = $soldTransactions->sum('quantity');

        $profit = $soldTransactions->sum(function ($t) {
            $gross = ($t->unit_price - $t->unit_cost) * $t->quantity;
            return $gross - $t->discount;
        });

        return [
            'name'          => $p->name,
            'quantity'      => $p->quantity,
            'cost_price'    => $p->cost_price,
            'selling_price' => $p->selling_price,
            'total_sold'    => $totalSoldQty,
            'profit'        => $profit,
            'discount'      => $soldTransactions->sum('discount'),

            'is_closed'     => $soldTransactions->every(fn($t) => $t->is_closed),
            'closed_at'     => optional($soldTransactions->max('closed_at')),
        ];
    });

    $total_profit   = $products->sum('profit');
    $total_discount = $products->sum('discount');

    return view('reports.stock', compact('products', 'total_stock_value', 'total_profit', 'total_discount'));
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
            'discount'   => 'nullable|numeric|min:0',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->quantity < $data['quantity']) {
            return back()->withInput()->withErrors(['quantity' => 'Not enough stock for this product.']);
        }

        $data['unit_price'] = $data['unit_price'] ?? $product->selling_price;
        $data['unit_cost']  = $product->cost_price;
        $data['type']       = StockTransaction::TYPE_OUT;
        $data['discount']   = $data['discount'] ?? 0;
        $data['is_closed']  = false;

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

        $data['unit_cost']  = $data['unit_cost'] ?? $product->cost_price;
        $data['unit_price'] = $product->selling_price;
        $data['type']       = StockTransaction::TYPE_IN;
        $data['discount']   = 0; // no discount on purchase
        $data['is_closed']  = false;

        StockTransaction::create($data);

        $product->quantity += $data['quantity'];
        $product->save();

        return redirect()->route('transactions.index')->with('success', 'Purchase recorded.');
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

    // ----------------- CLOSE SALES -----------------
    public function closeSales()
    {
        $today = Carbon::today();

        // Get today's unclosed sales
        $transactions = StockTransaction::whereDate('created_at', $today)
            ->where('type', StockTransaction::TYPE_OUT)
            ->where('is_closed', false)
            ->get();

        if ($transactions->isEmpty()) {
            return back()->with('success', 'No open sales to close for today.');
        }

        // Close each sale
        foreach ($transactions as $t) {
            $t->is_closed = true;
            $t->closed_at = now();
            $t->save();
        }

        // Send summary email to owner
        $ownerEmail = "kelvinkifunda077@gmail.com"; // ✅ you can also load from .env
        Mail::to($ownerEmail)->send(new SalesClosedSummary($transactions));

        return back()->with('success', 'Today\'s sales have been closed and sent to the owner.');
    }

    // ----------------- CLOSED TRANSACTIONS -----------------
public function closedTransactions()
{
    // Get all closed sales (type = 'out')
    $closedTransactions = StockTransaction::with('product')
        ->where('type', StockTransaction::TYPE_OUT)
        ->where('is_closed', true)
        ->orderBy('closed_at', 'desc')
        ->get();

    return view('reports.closed_transactions', compact('closedTransactions'));
}

}
