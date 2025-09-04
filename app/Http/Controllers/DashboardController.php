<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter dates
        $start = $request->query('start') 
            ? Carbon::parse($request->query('start'))->startOfDay() 
            : Carbon::now()->startOfMonth();
        $end = $request->query('end') 
            ? Carbon::parse($request->query('end'))->endOfDay() 
            : Carbon::now()->endOfDay();

        // Revenue, COGS and Discounts in selected range
        $stats = StockTransaction::where('type', StockTransaction::TYPE_OUT)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('
                COALESCE(SUM(unit_price * quantity),0) as revenue,
                COALESCE(SUM(unit_cost * quantity),0) as cost,
                COALESCE(SUM(discount),0) as discount
            ')
            ->first();

        $revenue  = (float) $stats->revenue;
        $cogs     = (float) $stats->cost;
        $discount = (float) $stats->discount;
        $profit   = $revenue - $cogs - $discount;

        // Global stats
        $total_products = Product::count();
        $low_stock = Product::whereColumn('quantity', '<=', DB::raw('1'))->get();
        $stock_value = Product::selectRaw('COALESCE(SUM(quantity * cost_price),0) as value')->first()->value;

        // Chart data
        $products = Product::all();
        $labels = $products->pluck('name'); // X-axis labels

        $stockData = $products->map(fn($p) => $p->cost_price * $p->quantity);

        $profitData = $products->map(function($p){
            $sold = $p->transactions()->where('type', StockTransaction::TYPE_OUT)->get();
            return $sold->sum(fn($t) => (($t->unit_price - $t->unit_cost) * $t->quantity) - $t->discount);
        });

        $discountData = $products->map(function($p){
            return $p->transactions()
                     ->where('type', StockTransaction::TYPE_OUT)
                     ->sum('discount');
        });

        return view('dashboard', compact(
            'start','end','revenue','cogs','profit','discount',
            'total_products','low_stock','stock_value',
            'labels','stockData','profitData','discountData'
        ));
    }
}
