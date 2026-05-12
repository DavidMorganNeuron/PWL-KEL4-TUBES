<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\OrderFlowController;
use App\Http\Controllers\PaymentController;
Route::get('/', function () {
    return view('main');
})->name('main');
=======
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderFlowController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;

>>>>>>> c62b95c5c7200321d10c86f7334781591c3e6db7

// File Arsitektur URL (Web Routes) - memetakan semua alamat URL website Pod's ke fungsi yang tepat.

// login, register, logout
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

<<<<<<< HEAD
Route::get('/account', function () {
    return view('account');
})->name('account');

Route::get('/', fn() => redirect('/orders/branch'));

/* ORDER FLOW */
Route::prefix('orders')->group(function () {

    Route::get('/branch', [OrderFlowController::class, 'branch']);
    Route::post('/branch', [OrderFlowController::class, 'setBranch']);

    Route::get('/menu', [OrderFlowController::class, 'menu']);

    Route::post('/cart/add', [OrderFlowController::class, 'addToCart']);

    Route::get('/checkout', [OrderFlowController::class, 'checkout']);
    Route::post('/checkout', [OrderFlowController::class, 'storeOrder']);
});

/* PAYMENT */
Route::get('/payment/{id}', [PaymentController::class, 'show']);
Route::post('/payment/{id}', [PaymentController::class, 'confirm']);

Route::get('/success/{id}', [PaymentController::class, 'success']);
=======
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
    
    // Halaman Utama menggunakan struktur folder baru
    Route::get('/', function () { return view('customer.main'); })->name('main');
    
    // Fitur Akun & Riwayat (Tahap 8)
    Route::get('/history', [CustomerController::class, 'history'])->name('history');
    Route::get('/account', [CustomerController::class, 'account'])->name('account');

    // (Rute OrderFlow dan Payment tetap sama seperti sebelumnya, 
    // karena penyesuaian folder view sudah kita lakukan di dalam controllernya)
    Route::get('/order/branch', [OrderFlowController::class, 'branch'])->name('orders.branch');
    Route::post('/order/branch', [OrderFlowController::class, 'setBranch']);
    
    Route::get('/order/menu', [OrderFlowController::class, 'menu'])->name('orders.menu');
    Route::post('/order/cart/add', [OrderFlowController::class, 'addToCart']);
    
    Route::get('/order/checkout', [OrderFlowController::class, 'checkout'])->name('orders.checkout');
    Route::post('/order/checkout', [OrderFlowController::class, 'storeOrder']);

    Route::get('/payment/{id}', [PaymentController::class, 'show']);
    Route::post('/payment/{id}', [PaymentController::class, 'confirm']);
    Route::get('/success/{id}', [PaymentController::class, 'success']);
});
>>>>>>> c62b95c5c7200321d10c86f7334781591c3e6db7
