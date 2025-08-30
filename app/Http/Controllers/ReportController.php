<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;

class ReportController extends Controller
{
    public function stockReport()
    {
        $products = Product::all();

        // Total stock value (current stock * cost price)
        $total_stock_value = $products->sum(function($p) {
            return $p->cost_price * $p->quantity;
        });

        // Total profit based on sold transactions (type = 'out')
        $total_profit = StockTransaction::where('type', 'out')->get()->sum(function($t) {
            return ($t->unit_price - $t->unit_cost) * $t->quantity;
        });

        // Prepare per-product profit and stock value
        $products = $products->map(function($p) {
            $sold = $p->transactions()->where('type','out')->get();
            $profit = $sold->sum(function($t) {
                return ($t->unit_price - $t->unit_cost) * $t->quantity;
            });
            return [
                'name' => $p->name,
                'sku' => $p->sku,
                'quantity' => $p->quantity,
                'cost_price' => $p->cost_price,
                'selling_price' => $p->selling_price,
                'stock_value' => $p->cost_price * $p->quantity,
                'profit' => $profit,
            ];
        });

        return view('reports.stock', compact('products', 'total_stock_value', 'total_profit'));
    }
}
