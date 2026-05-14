<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;


// Controller Pelanggan (Customer Area)
// Tujuan: Menangani logika halaman profil dan riwayat, diarahkan langsung ke folder 'resources/views/customer/'
class CustomerController extends Controller
{
    public function account() {
        // Memanggil file dari resources/views/customer/account.blade.php
        return view('customer.account');
    }

    public function history()
    {
        $orders = Order::with(['branch', 'items.product'])
                       ->where('user_id', Auth::id())
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('customer.history', compact('orders'));
    }
}