<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Controller Pembayaran - mengatur eksekusi final ketika pengguna mengeklik "BAYAR".
class PaymentController extends Controller
{
    public function show($id) {
        $order = Order::findOrFail($id);
        return view('customer.orders.payment', compact('order'));
    }

    public function confirm($id) {
        $payment = Payment::where('order_id', $id)->firstOrFail();
        $order = Order::findOrFail($id);

        // Validasi keamanan: Pastikan nota ini benar-benar milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ilegal.');
        }

        // Eksekusi 1: Update status pembayaran menjadi sukses
        $payment->update([
            'status' => 'success',
            'paid_at' => now()
        ]);

        // Eksekusi 2: Update status pesanan agar masuk ke layar Dapur (KDS)
        $order->update([
            'status' => 'paid'
        ]);

        // Persiapan menentukan nama tabel stok spesifik milik cabang terkait
        $branchName = $order->branch->name;
        $stockTable = 'stock_branch_' . strtolower(str_replace([' ', '.'], ['_', ''], $branchName));

        // Ambil daftar barang dari nota, lalu lakukan pemotongan stok
        foreach ($order->items as $item) {
            
            // Eksekusi 3: Potong stok fisik di dapur, dan lepaskan tahanan stok sementara (reserved)
            DB::table($stockTable)->where('product_id', $item->product_id)->update([
                'physical_qty' => DB::raw("physical_qty - {$item->qty}"),
                'reserved_qty' => DB::raw("reserved_qty - {$item->qty}")
            ]);

            // Eksekusi 4: Catat riwayat keluarnya barang ke tabel stock_log
            DB::table('stock_log')->insert([
                'branch_id' => $order->branch_id,
                'product_id' => $item->product_id,
                'user_id' => Auth::id(),
                'order_id' => $order->id_orders,
                'activity_type' => 'sale', // Kode aktivitas penjualan
                'quantity_change' => -$item->qty, // Diberi tanda minus karena stok berkurang
                'created_at' => now(),
            ]);
        }

        // Transaksi berurutan selesai, arahkan ke halaman sukses
        return redirect('/success/' . $id);
    }

    public function success($id) {
        $order = Order::findOrFail($id);
        return view('customer.orders.success', compact('order'));
    }
}