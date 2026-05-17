<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Concerns\ResolvesStockTable;
use App\Models\Order;
use App\Models\Payment;


// PaymentController - mengelola halaman pembayaran simulasi, pemotongan stok fisik, dan pencatatan stock_log.
class PaymentController extends Controller
{
    use ResolvesStockTable;
 
    public function show($id)
    {
        // pastikan order milik user yang login
        $order = Order::with(['items.product', 'branch'])
                      ->where('id_orders', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();
 
        $payment = Payment::where('order_id', $id)->firstOrFail();
 
        // arahkan ke success jika sudah dibayar sebelumnya
        if ($order->status !== 'pending_payment') {
            return redirect('/success/' . $id);
        }
 
        return view('customer.orders.payment', compact('order', 'payment'));
    }
 
    public function confirm($id)
    {
        $order = Order::with(['items.product', 'branch'])
                      ->where('id_orders', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();
 
        // jangan proses dua kali
        if ($order->status !== 'pending_payment') {
            return redirect('/success/' . $id);
        }
 
        $payment = Payment::where('order_id', $id)
                          ->where('status', 'pending')
                          ->firstOrFail();
 
        $stockTable = $this->resolveStockTable($order->branch->name);
 
        // pembayaran selalu sukses
        $payment->update([
            'status'  => 'success',
            'paid_at' => now(),
        ]);
 
        $order->update(['status' => 'paid']);
 
        // potong physical_qty + lepas reserved_qty + catat ke stock_log
        foreach ($order->items as $item) {
            DB::table($stockTable)
              ->where('product_id', $item->product_id)
              ->update([
                  'physical_qty' => DB::raw('physical_qty - ' . (int) $item->qty),
                  'reserved_qty' => DB::raw('reserved_qty - ' . (int) $item->qty),
              ]);
 
            DB::table('stock_log')->insert([
                'branch_id'       => $order->branch_id,
                'product_id'      => $item->product_id,
                'user_id'         => Auth::id(),
                'order_id'        => $order->id_orders,
                'request_id'      => null,
                'activity_type'   => 'sale',
                'quantity_change' => -(int) $item->qty,
                'created_at'      => now(),
            ]);
        }
 
        Log::info("PaymentController: Order #{$order->order_number} berhasil dibayar oleh user " . Auth::id());
 
        return redirect('/success/' . $id);
    }
 
    public function success($id)
    {
        $order = Order::with(['items.product', 'branch'])
                      ->where('id_orders', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();
 
        return view('customer.orders.success', compact('order'));
    }
 
    /*
        Membatalkan order yang masih pending_payment.
        Melepas reserved_qty agar stok tidak terus-menerus terkunci.
    */
    public function abandon($id)
    {
        $order = Order::with(['items', 'branch'])
                      ->where('id_orders', $id)
                      ->where('user_id', Auth::id())
                      ->where('status', 'pending_payment')
                      ->first();
 
        // jika order tidak ditemukan atau sudah dibayar, abaikan tidak perlu diproses
        if (!$order) {
            return response()->json(['ok' => true]);
        }
 
        $stockTable = $this->resolveStockTable($order->branch->name);
 
        $order->update([
            'status'        => 'canceled',
            'cancel_reason' => 'Gagal memproses order.',
        ]);
 
        // lepas reserved_qty yang sebelumnya di-lock saat storeOrder
        foreach ($order->items as $item) {
            DB::table($stockTable)
              ->where('product_id', $item->product_id)
              ->update([
                  'reserved_qty' => DB::raw('GREATEST(0, reserved_qty - ' . (int) $item->qty . ')'),
              ]);
        }
 
        Log::info("PaymentController: Order #{$order->order_number} diabaikan/timeout oleh user " . Auth::id());
 
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['ok' => true]);
        }
 
        return redirect()->route('history');
    }
}