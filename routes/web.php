<?php

use Illuminate\Support\Facades\Route;

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