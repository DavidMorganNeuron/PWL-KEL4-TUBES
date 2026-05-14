<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderFlowController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
// GUEST ROUTES (Login & Register)

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister']);

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ADMIN ROUTES (Role: Admin Pusat)

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Katalog Menu
    Route::get('/products', [AdminController::class, 'productsIndex'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'productsCreate'])->name('products.create');
    Route::post('/products', [AdminController::class, 'productsStore'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'productsEdit'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'productsUpdate'])->name('products.update');
    Route::patch('/products/{id}/toggle', [AdminController::class, 'productsToggle'])->name('products.toggle');

    // Manajemen Promo
    Route::get('/promos', [AdminController::class, 'promosIndex'])->name('promos.index');
    Route::get('/promos/create', [AdminController::class, 'promosCreate'])->name('promos.create');
    Route::post('/promos', [AdminController::class, 'promosStore'])->name('promos.store');
    Route::get('/promos/{id}/edit', [AdminController::class, 'promosEdit'])->name('promos.edit');
    Route::put('/promos/{id}', [AdminController::class, 'promosUpdate'])->name('promos.update');
    Route::patch('/promos/{id}/toggle', [AdminController::class, 'promosToggle'])->name('promos.toggle');

    // Validasi Request Restock
    Route::get('/requests', [AdminController::class, 'requestsIndex'])->name('requests');
    Route::patch('/requests/{id}/action', [AdminController::class, 'requestAction'])->name('requests.action');

    // Data Manager (Read-Only)
    Route::get('/managers', [AdminController::class, 'managersIndex'])->name('managers');
});
// MANAGER ROUTES (Role: Manager Cabang)

Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    
    // Kitchen Display System (Antrean Masak)
    Route::get('/kds', [ManagerController::class, 'kds'])->name('kds');
    Route::patch('/kds/{id}/update', [ManagerController::class, 'kdsUpdate'])->name('kds.update');

    // Pemantauan Stok
    Route::get('/stocks', [ManagerController::class, 'stocks'])->name('stocks');
    
    // Pengajuan Restock
    Route::get('/request-form', [ManagerController::class, 'requestForm'])->name('request.form');
    Route::post('/request-form', [ManagerController::class, 'requestStore'])->name('request.store');

    // Laporan Cabang
    Route::get('/reports', [ManagerController::class, 'reports'])->name('reports');
});


// CUSTOMER ROUTES (Role: Customer)

Route::middleware(['auth', 'role:customer'])->group(function () {
    // Halaman Utama (Pemilihan Cabang)
    Route::get('/', function () { return view('cust.main'); })->name('main');
    
    // Fitur Akun & Riwayat
    Route::get('/history', [CustomerController::class, 'history'])->name('history');
    Route::get('/account', [CustomerController::class, 'account'])->name('account');

    // Alur Order
    Route::get('/order/branch', [OrderFlowController::class, 'branch'])->name('orders.branch');
    Route::post('/order/branch', [OrderFlowController::class, 'setBranch']);
    
    Route::get('/order/menu', [OrderFlowController::class, 'menu'])->name('orders.menu');
    Route::post('/order/cart/add', [OrderFlowController::class, 'addToCart']);
    
    Route::get('/order/checkout', [OrderFlowController::class, 'checkout'])->name('orders.checkout');
    Route::post('/order/checkout', [OrderFlowController::class, 'storeOrder']);

    // Pembayaran & Tracking
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('cust.payment.show');
    Route::post('/payment/{id}', [PaymentController::class, 'confirm'])->name('cust.payment.process');
    Route::get('/tracking/{id}', [PaymentController::class, 'tracking'])->name('cust.tracking'); // Added for Live Tracking
});