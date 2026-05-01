<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderFlowController;
use App\Http\Controllers\PaymentController;


// File Arsitektur URL (Web Routes) - memetakan semua alamat URL website Pod's ke fungsi yang tepat.

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
    // Rute lanjutan untuk Manajemen Produk, Promo, dan Validasi Request akan ditambahkan di sini nanti
});


// role = manager
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard Manager Cabang';
    })->name('dashboard');
    // Rute lanjutan untuk Kitchen Display System (KDS) dan Laporan akan ditambahkan di sini nanti
});


// role = customer
Route::middleware(['auth', 'role:customer'])->group(function () {

    // Halaman Statis & Akun
    Route::get('/', function () {
        return view('main');
    })->name('main');
    Route::get('/history', function () {
        return view('history');
    })->name('history');
    Route::get('/account', function () {
        return view('account');
    })->name('account');

    // Alur Pemesanan
    Route::get('/order/branch', [OrderFlowController::class, 'branch'])->name('orders.branch');
    Route::post('/order/branch', [OrderFlowController::class, 'setBranch']);

    Route::get('/order/menu', [OrderFlowController::class, 'menu'])->name('orders.menu');
    Route::post('/order/cart/add', [OrderFlowController::class, 'addToCart']);

    Route::get('/order/checkout', [OrderFlowController::class, 'checkout'])->name('orders.checkout');
    Route::post('/order/checkout', [OrderFlowController::class, 'storeOrder']);

    // Alur Pembayaran
    Route::get('/payment/{id}', [PaymentController::class, 'show']);
    Route::post('/payment/{id}', [PaymentController::class, 'confirm']);
    Route::get('/success/{id}', [PaymentController::class, 'success']);
});
