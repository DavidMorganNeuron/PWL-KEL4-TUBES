<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderFlowController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManagerController;

// login, register, logout
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister']);

// hanya orang yang login yang bisa logout
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// role = admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard Admin Pusat';
    })->name('dashboard');
    // Rute lanjutan untuk Manajemen Produk, Promo, dan Validasi Request akan ditambahkan di sini nanti ya ges
});


// role = manager
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
 
    // dashboard
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
 
    // kitchen display system
    Route::get('/kds', [ManagerController::class, 'kds'])->name('kds');
 
    // aksi status pesanan
    Route::post('/orders/{id}/cook',   [ManagerController::class, 'cookOrder'])->name('orders.cook');
    Route::post('/orders/{id}/done',   [ManagerController::class, 'doneOrder'])->name('orders.done');
    Route::post('/orders/{id}/cancel', [ManagerController::class, 'cancelOrder'])->name('orders.cancel');
 
    // monitoring stok lokal
    Route::get('/stock', [ManagerController::class, 'stock'])->name('stock');
 
    // laporan penjualan
    Route::get('/report', [ManagerController::class, 'report'])->name('report');
 
    // pengajuan restock
    Route::get('/request',  [ManagerController::class, 'requestForm'])->name('request_form');
    Route::post('/request', [ManagerController::class, 'storeRequest'])->name('request_form.store');
});


// role = customer
Route::middleware(['auth', 'role:customer'])->group(function () {
    
    // Halaman Utama
    Route::get('/', function () { return view('customer.main'); })->name('main');
    
    // Fitur Akun & Riwayat
    Route::get('/history', [CustomerController::class, 'history'])->name('history');
    Route::get('/account', [CustomerController::class, 'account'])->name('account');

    Route::get('/order/branch', [OrderFlowController::class, 'branch'])->name('orders.branch');
    Route::post('/order/branch', [OrderFlowController::class, 'setBranch']);
 
    // Fitur Order
    Route::get('/order/menu', [OrderFlowController::class, 'menu'])->name('orders.menu');
    Route::post('/order/cart/add', [OrderFlowController::class, 'addToCart']);
    Route::post('/order/cart/remove', [OrderFlowController::class, 'removeFromCart']);
    
    Route::get('/order/checkout', [OrderFlowController::class, 'checkout'])->name('orders.checkout');
    Route::post('/order/checkout', [OrderFlowController::class, 'storeOrder']);

    Route::get('/payment/{id}', [PaymentController::class, 'show']);
    Route::post('/payment/{id}', [PaymentController::class, 'confirm']);
    Route::get('/success/{id}', [PaymentController::class, 'success']);

    // dipanggil saat customer meninggalkan halaman payment
    Route::post('/payment/{id}/abandon', [PaymentController::class, 'abandon'])->name('payment.abandon');
});