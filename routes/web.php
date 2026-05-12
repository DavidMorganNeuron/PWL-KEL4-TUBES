<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderFlowController;
use App\Http\Controllers\PaymentController;
Route::get('/', function () {
    return view('main');
})->name('main');

Route::get('/orders', function () {
    return view('orders.branch');
})->name('orders.branch');

Route::get('/history', function () {
    return view('history');
})->name('history');

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