<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderFlowController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;

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
    Route::get('/dashboard', function () {
        return redirect()->route('manager.dashboard');
    })->name('dashboard');

    // kitchen display system
    Route::get('/kds', function () {
        return view('manager.kds');
    })->name('kds');

    // monitoring stok lokal
    Route::get('/stock', function () {
        return view('manager.stock');
    })->name('stock');

    // request restock
    Route::get('/request_form', function () {
        return view('manager.request_form');
    })->name('request_form');
    Route::post('/request_form', function () {
        return back()->with('toast', 'Pengajuan berhasil dikirim!');
    })->name('request_form.store');

    // laporan penjualan cabang
    Route::get('/report', function () {
        return view('manager.report');
    })->name('report');

    // aksi status pesanan
    Route::patch('/orders/{id}/cook', function ($id) {
        return back()->with('toast', 'Pesanan mulai dimasak.');
    })->name('orders.cook');

    Route::patch('/orders/{id}/complete', function ($id) {
        return back()->with('toast', 'Pesanan selesai dan siap diambil!');
    })->name('orders.complete');

    Route::patch('/orders/{id}/cancel', function ($id) {
        return back()->with('toast', 'Pesanan berhasil dibatalkan.');
    })->name('orders.cancel');
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
});