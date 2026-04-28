<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function show($id) {
        $order = Order::findOrFail($id);
        return view('order.payment', compact('order'));
    }

    public function confirm($id) {
        $payment = Payment::where('order_id', $id)->firstOrFail();

        $payment->update([
            'status' => 'success',
            'paid_at' => now()
        ]);

        Order::where('id_orders', $id)->update([
            'status' => 'paid'
        ]);

        return redirect('/success/' . $id);
    }

    public function success($id) {
        $order = Order::findOrFail($id);
        return view('order.success', compact('order'));
    }
}