<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Concerns\ResolvesStockTable;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;


// OrderFlowController - mengelola alur pemesanan pelanggan dari pilih cabang hingga buat order.
class OrderFlowController extends Controller
{
    use ResolvesStockTable;
    /* ================================================================
       STEP 1: PILIH CABANG
    ================================================================ */
    public function branch()
    {
        // load semua cabang sekaligus
        $branches = Branch::orderBy('name')->get();

        return view('customer.orders.branch', compact('branches'));
    }

    public function setBranch(Request $request)
    {
        $request->validate([
            'branch_id' => ['required', 'integer', 'exists:branches,id_branches'],
        ]);

        // reset cart agar tidak ada campuran produk antar cabang
        session()->forget('cart');
        session(['branch_id' => $request->branch_id]);

        return redirect()->route('orders.menu');
    }

    /* ================================================================
       STEP 2: MENU + CART
    ================================================================ */
    public function menu()
    {
        if (!session('branch_id')) {
            return redirect()->route('orders.branch')
                             ->with('error', 'Silakan pilih cabang terlebih dahulu.');
        }

        // load kategori sekaligus
        $products = Product::where('is_available', true)
                           ->with('category')
                           ->orderBy('name')
                           ->get();

        $cart = session('cart', []);

        return view('customer.orders.menu', compact('products', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id_products'],
        ]);

        // verifikasi produk masih tersedia saat ditambahkan
        $product = Product::where('id_products', $request->product_id)
                          ->where('is_available', true)
                          ->first();

        if (!$product) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        $cart = session('cart', []);
        $id   = (string) $request->product_id;

        $cart[$id] = ($cart[$id] ?? 0) + 1;
        session(['cart' => $cart]);

        return back()->with('toast', "{$product->name} ditambahkan ke keranjang.");
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cart = session('cart', []);
        $id   = (string) $request->product_id;

        if (isset($cart[$id])) {
            $cart[$id]--;
            if ($cart[$id] <= 0) {
                unset($cart[$id]);
            }
        }

        session(['cart' => $cart]);

        return back();
    }

    /* ================================================================
       STEP 3: CHECKOUT PREVIEW
    ================================================================ */

    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('orders.menu')
                             ->with('error', 'Keranjang belanja kamu kosong.');
        }

        // ambil semua produk di cart sekaligus dengan whereIn
        $productIds = array_keys($cart);
        $products   = Product::whereIn('id_products', $productIds)
                             ->where('is_available', true)
                             ->get()
                             ->keyBy('id_products'); // index by PK

        $branch = Branch::find(session('branch_id'));

        if (!$branch) {
            return redirect()->route('orders.branch')
                             ->with('error', 'Sesi cabang tidak valid, silakan pilih ulang.');
        }

        return view('customer.orders.checkout', compact('cart', 'products', 'branch'));
    }

    /* ================================================================
       STEP 4: BUAT ORDER + SOFT-LOCK STOK
    ================================================================ */

    public function storeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'string', 'in:qris,ewallet'],
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('orders.menu')
                             ->with('error', 'Keranjang belanja kamu kosong.');
        }

        $branchId = session('branch_id');
        $branch   = Branch::find($branchId);

        if (!$branch) {
            return redirect()->route('orders.branch')
                             ->with('error', 'Sesi cabang tidak valid, silakan pilih ulang.');
        }

        // resolve nama tabel stok cabang menggunakan helper terpusat
        $stockTable = $this->resolveStockTable($branch->name);

        // ambil semua produk di cart sekaligus
        $productIds = array_keys($cart);
        $products   = Product::whereIn('id_products', $productIds)
                             ->where('is_available', true)
                             ->get()
                             ->keyBy('id_products');

        // pastikan semua produk di cart masih valid
        foreach ($cart as $productId => $qty) {
            if (!$products->has($productId)) {
                return back()->with('error', 'Salah satu produk di keranjang tidak lagi tersedia. Silakan periksa keranjang.');
            }
        }

        // hitung subtotal
        $subtotal = 0;
        foreach ($cart as $productId => $qty) {
            $subtotal += $products[$productId]->base_price * $qty;
        }

        // buat nota order
        $order = Order::create([
            'branch_id'      => $branchId,
            'user_id'        => Auth::id(),
            'order_number'   => $this->generateOrderNumber(),
            'subtotal'       => $subtotal,
            'total_discount' => 0,
            'grand_total'    => $subtotal,
            'status'         => 'pending_payment',
        ]);

        // insert order_items + soft-lock stok
        foreach ($cart as $productId => $qty) {
            $product = $products[$productId];

            OrderItem::create([
                'order_id'       => $order->id_orders,
                'product_id'     => $productId,
                'qty'            => $qty,
                'base_price'     => $product->base_price,
                'discount_amount'=> 0,
                'subtotal_price' => $product->base_price * $qty,
            ]);

            // soft-lock: tambah reserved_qty, tidak boleh melebihi physical_qty
            $affected = DB::table($stockTable)
                ->where('product_id', $productId)
                ->whereRaw('(physical_qty - reserved_qty) >= ?', [$qty])
                ->increment('reserved_qty', $qty);

            if ($affected === 0) {
                // stok tidak cukup — batalkan order yang baru dibuat
                $order->update(['status' => 'canceled', 'cancel_reason' => 'Stok tidak mencukupi saat checkout.']);
                OrderItem::where('order_id', $order->id_orders)->delete();

                Log::warning("OrderFlowController: stok tidak cukup untuk produk {$productId} di {$stockTable}");

                return back()
                    ->with('error', "Stok {$product->name} tidak mencukupi. Silakan kurangi jumlah atau pilih menu lain.");
            }
        }

        // buat record payment
        Payment::create([
            'order_id' => $order->id_orders,
            'method'   => $request->payment_method,
            'status'   => 'pending',
        ]);

        // bersihkan cart dari session
        session()->forget('cart');

        return redirect('/payment/' . $order->id_orders);
    }

    // menghasilkan order number unik. format: PODS-YYYYMMDD-XXXXXX (6 karakter hex uppercase)
    private function generateOrderNumber(): string
    {
        return 'PODS-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }
}