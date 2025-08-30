<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockTransactionController;

// Route::get('/', function () {
//     return view('welcome');
// });




Route::get('/', [AuthController::class, 'showLogin'])->name('login');


Route::get('/index', [DashboardController::class, 'index'])->name('index.page');



Route::middleware(['auth'])->group(function() {
    Route::put('/profile', [ProfileController::class, 'update'])->name('user.update-profile');

    Route::post('/profile/password', [ProfileController::class,'updatePassword'])->name('profile.update-password');
    Route::resource('products', ProductController::class);
Route::resource('transactions', StockTransactionController::class)->only(['index','create','store','show']);
Route::get('/reports/stock', [\App\Http\Controllers\ReportController::class, 'stockReport'])->name('reports.stock');
});



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

