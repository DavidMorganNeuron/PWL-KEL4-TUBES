<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


// Controller Alur Pemesanan - mengatur jalannya pelanggan dari pilih cabang sampai ke gerbang pembayaran.
class OrderFlowController extends Controller
{
    /* =======================
       STEP 1: PILIH CABANG
    ======================= */
    public function branch() {
        $branches = Branch::all();
        return view('orders.branch', compact('branches'));
    }

    public function setBranch(Request $request) {
        session(['branch_id' => $request->branch_id]);
        session()->forget('cart'); // Reset cart jika pindah cabang

        return redirect('/order/menu');
    }

    /* =======================
       STEP 2: MENU + CART
    ======================= */
    public function menu() {
        if (!session('branch_id')) {
            return redirect('/order/branch');
        }

        $products = Product::where('is_available', true)->get();
        $cart = session('cart', []);

        return view('orders.menu', compact('products', 'cart'));
    }

    public function addToCart(Request $request) {
        $cart = session('cart', []);
        $id = $request->product_id;
        $cart[$id] = ($cart[$id] ?? 0) + 1;
        
        session(['cart' => $cart]);

        return back();
    }

    /* =======================
       STEP 3: CHECKOUT
    ======================= */
    public function checkout() {
        if (!session('cart')) {
            return redirect('/order/menu');
        }
        return view('orders.checkout');
    }

    /* =======================
       STEP 4: CREATE ORDER
    ======================= */
    public function storeOrder(Request $request) {
        $cart = session('cart');

        if (!$cart) {
            return redirect('/order/menu');
        }

        $subtotal = 0;
        $branchId = session('branch_id');
        
        // Mencari tahu nama tabel stok spesifik untuk cabang yang dipilih
        // Contoh: dari nama 'Dr. Mansyur' diubah menjadi format 'stock_branch_dr_mansyur'
        $branchName = Branch::find($branchId)->name;
        $stockTable = 'stock_branch_' . strtolower(str_replace([' ', '.'], ['_', ''], $branchName));

        // Hitung total belanja dari semua barang di keranjang
        foreach ($cart as $product_id => $qty) {
            $product = Product::find($product_id);
            if (!$product) continue;
            $subtotal += $product->base_price * $qty;
        }

        // Eksekusi 1: Pembuatan Nota Utama (Tabel orders)
        $order = Order::create([
            'branch_id' => $branchId,
            'user_id' => Auth::id(), // Mengambil ID dari pelanggan yang sedang login
            'order_number' => 'ORD-' . time(),
            'subtotal' => $subtotal,
            'total_discount' => 0,
            'grand_total' => $subtotal,
            'status' => 'pending_payment' // Status awal menunggu pembayaran
        ]);

        // Eksekusi 2: Memasukkan Rincian Pesanan dan Menahan Stok
        foreach ($cart as $product_id => $qty) {
            $product = Product::find($product_id);
            if (!$product) continue;

            // Simpan rincian barang ke tabel order_items
            OrderItem::create([
                'order_id' => $order->id_orders,
                'product_id' => $product_id,
                'qty' => $qty,
                'base_price' => $product->base_price,
                'discount_amount' => 0,
                'subtotal_price' => $product->base_price * $qty
            ]);

            // Eksekusi 3: Fitur Soft-Lock (Tahan stok sementara)
            // Query builder biasa untuk menambah nilai di kolom reserved_qty
            DB::table($stockTable)->where('product_id', $product_id)->increment('reserved_qty', $qty);
        }

        // Eksekusi 4: Persiapkan data gerbang pembayaran (Tabel payments)
        Payment::create([
            'order_id' => $order->id_orders,
            'method' => $request->payment_method,
            'status' => 'pending'
        ]);

        // Bersihkan session keranjang belanja karena sudah menjadi pesanan
        session()->forget('cart');

        return redirect('/payment/' . $order->id_orders);
    }
}