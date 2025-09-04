<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockTransactionController;


Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('layouts.app'); // admin dashboard
    })->name('admin.dashboard');

    Route::get('/customer/dashboard', function () {
        return view('layouts.customer'); // customer dashboard
    })->name('customer.dashboard');
});






Route::get('/', [AuthController::class, 'showLogin'])->name('login');


Route::get('/index', [DashboardController::class, 'index'])->name('index.page');




Route::middleware(['auth'])->group(function () {
    // Profile
    Route::put('/profile', [ProfileController::class, 'update'])->name('user.update-profile');
    Route::post('/profile/password', [ProfileController::class,'updatePassword'])->name('profile.update-password');

    // Products
    Route::resource('products', ProductController::class);

    // Sales
    Route::get('/sales/create', [StockTransactionController::class, 'createSale'])->name('sales.create');
    Route::post('/sales', [StockTransactionController::class, 'storeSale'])->name('sales.store');

    // Purchases
    Route::get('/purchases/create', [StockTransactionController::class, 'createPurchase'])->name('purchases.create');
    Route::post('/purchases', [StockTransactionController::class, 'storePurchase'])->name('purchases.store');

    // Transactions listing & details
    Route::resource('transactions', StockTransactionController::class)->only(['index','show']);

    // Reports
    Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');

    // Route to close today's sales and send summary email
    Route::post('/transactions/close-sales', [ReportController::class, 'closeSales'])->name('transactions.closeSales');

    //route to view closed transactions
    Route::get('/reports/closed', [ReportController::class, 'closedTransactions'])->name('report.closed');
});






Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Register Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');




