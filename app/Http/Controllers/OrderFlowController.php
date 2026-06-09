<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Concerns\ResolvesStockTable;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;


// OrderFlowController - mengelola alur pemesanan pelanggan dari pilih cabang hingga buat order.
class OrderFlowController extends Controller
{
    use ResolvesStockTable;
    /* ================================================================
       STEP 1: PILIH CABANG (sekarang menyatu dengan halaman Menu)
    ================================================================ */
    public function branch()
    {
        // jika sudah masuk menu, tetap di menu — redirect saja
        return redirect()->route('orders.menu');
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
       STEP 2: MENU + CART (dengan pemilihan cabang terintegrasi)
    ================================================================ */
    public function menu()
    {
        // load daftar cabang untuk selector
        $branches = Branch::where('is_active', true)
            ->where('is_closing', false)
            ->orderBy('name')
            ->get();

        $branch = null;
        if (session('branch_id')) {
            $branch = Branch::find(session('branch_id'));
            if (!$branch) {
                session()->forget('branch_id');
            }
        }

        // jika belum pilih cabang, tampilkan halaman pemilihan cabang
        if (!$branch) {
            return view('customer.orders.menu', compact('branches', 'branch'));
        }

        // load semua produk yang tersedia
        $products = Product::where('is_available', true)
                           ->with('category')
                           ->orderBy('name')
                           ->get();

        $cart = session('cart', []);

        // resolve nama tabel stok cabang
        $stockTable = $this->resolveStockTable($branch->name);

        // ambil data stok untuk cabang terpilih
        $stocks = DB::table($stockTable)
            ->select('product_id', DB::raw('(physical_qty - reserved_qty) as available_qty'))
            ->get()
            ->keyBy('product_id');

        // hitung diskon dari promo aktif — untuk SEMUA produk, bukan hanya cart
        $allProductIds = $products->pluck('id_products')->toArray();
        $promoDetails = $this->calculatePromoDetails($allProductIds, $branch->id_branches);
        $promoDiscounts = $promoDetails['amounts'];
        $promoDescriptions = $promoDetails['descriptions'];

        return view('customer.orders.menu', compact('products', 'cart', 'branches', 'branch', 'stocks', 'promoDiscounts', 'promoDescriptions'));
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

        // validasi jam operasional
        if (!$branch->isOpen()) {
            return redirect()->route('orders.menu')
                ->with('error', "Cabang {$branch->name} sedang tutup. Silakan pilih cabang lain atau pesan di jam operasional.");
        }

        // validasi stok: cek apakah ada item yang melebihi stok tersedia
        $stockTable = $this->resolveStockTable($branch->name);
        $stocks = DB::table($stockTable)
            ->select('product_id', DB::raw('(physical_qty - reserved_qty) as available_qty'))
            ->get()
            ->keyBy('product_id');

        foreach ($cart as $productId => $qty) {
            $avail = (int) ($stocks[$productId]->available_qty ?? 0);
            if ($qty > $avail) {
                $productName = $products->has($productId) ? $products[$productId]->name : 'Produk';
                return redirect()->route('orders.menu')
                    ->with('error', "{$productName} hanya tersedia {$avail} item. Jumlah pesanan ({$qty}) melebihi stok.");
            }
        }

        // hitung diskon dari promo aktif yang relevan
        $promoDiscounts = $this->calculatePromoDiscounts($productIds, $branch->id_branches);

        return view('customer.orders.checkout', compact('cart', 'products', 'branch', 'promoDiscounts'));
    }

    /* ================================================================
       STEP 4: BUAT ORDER + SOFT-LOCK STOK
    ================================================================ */

    public function storeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'string', 'in:qris,ewallet,QRIS,E_Wallet'],
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

        if (!$branch->isOpen()) {
            return redirect()->route('orders.menu')
                ->with('error', "Cabang {$branch->name} sedang tutup. Silakan pilih cabang lain.");
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

        // hitung subtotal dan diskon dari promo aktif
        $subtotal = 0;
        $totalDiscount = 0;
        $promoResult = $this->calculatePromoDiscounts($productIds, $branchId);
        $promoDiscounts = $promoResult['discounts'];
        $promoIds       = $promoResult['promoIds'];

        // akumulasi diskon per promo untuk menentukan promo utama pesanan
        $promoDiscountTotal = [];
        foreach ($cart as $productId => $qty) {
            $subtotal += $products[$productId]->base_price * $qty;
            $discountLine = ($promoDiscounts[$productId] ?? 0) * $qty;
            $totalDiscount += $discountLine;

            if (isset($promoIds[$productId]) && $promoIds[$productId] !== null) {
                $pid = $promoIds[$productId];
                $promoDiscountTotal[$pid] = ($promoDiscountTotal[$pid] ?? 0) + $discountLine;
            }
        }

        // promo dengan kontribusi diskon terbesar menjadi promo_id pesanan
        $bestPromoId = !empty($promoDiscountTotal)
            ? array_keys($promoDiscountTotal, max($promoDiscountTotal))[0]
            : null;

        // buat nota order
        $order = Order::create([
            'branch_id'      => $branchId,
            'user_id'        => Auth::id(),
            'order_number'   => $this->generateOrderNumber(),
            'promo_id'       => $bestPromoId,
            'subtotal'       => $subtotal,
            'total_discount' => $totalDiscount,
            'grand_total'    => max(0, $subtotal - $totalDiscount),
            'status'         => 'pending_payment',
        ]);

        // insert order_items + soft-lock stok
        foreach ($cart as $productId => $qty) {
            $product = $products[$productId];
            $discountPerItem = $promoDiscounts[$productId] ?? 0;

            OrderItem::create([
                'order_id'       => $order->id_orders,
                'product_id'     => $productId,
                'qty'            => $qty,
                'base_price'     => $product->base_price,
                'discount_amount'=> $discountPerItem,
                'subtotal_price' => max(0, ($product->base_price - $discountPerItem) * $qty),
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
            'method'   => $request->payment_method === 'qris' ? 'QRIS' : 'E_Wallet',
            'status'   => 'pending',
        ]);

        // bersihkan cart dari session
        session()->forget('cart');

        return redirect('/payment/' . $order->id_orders);
    }

    // menghitung diskon per produk dari promo aktif yang relevan (nasional + lokal cabang)
    // mengembalikan ['discounts' => [productId => amount], 'promo_ids' => [productId => promoId]]
    private function calculatePromoDiscounts(array $productIds, int $branchId): array
    {
        $now = now();
        $promos = Promo::where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where(function ($q) use ($branchId) {
                $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
            })
            ->with('products')
            ->get();

        $discounts = [];
        $promoIds  = [];
        foreach ($productIds as $pid) {
            $discounts[$pid] = 0;
            $promoIds[$pid]  = null;
        }

        foreach ($promos as $promo) {
            $promoProductIds = $promo->products->pluck('id_products')->toArray();

            foreach ($productIds as $pid) {
                if (!in_array($pid, $promoProductIds, true)) {
                    continue;
                }

                $product = Product::find($pid);
                if (!$product) continue;

                if ($promo->discount_type === 'percentage') {
                    $disc = round($product->base_price * $promo->discount_value / 100);
                } else {
                    $disc = (int) $promo->discount_value;
                }

                // ambil diskon terbesar jika ada beberapa promo untuk produk yg sama
                if ($disc > $discounts[$pid]) {
                    $discounts[$pid] = $disc;
                    $promoIds[$pid]  = $promo->id_promos;
                }
            }
        }

        return compact('discounts', 'promoIds');
    }

    // menghitung diskon + deskripsi promo untuk ditampilkan di grid menu
    private function calculatePromoDetails(array $productIds, int $branchId): array
    {
        $now = now();
        $promos = Promo::where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where(function ($q) use ($branchId) {
                $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
            })
            ->with('products')
            ->get();

        $amounts = [];
        $descriptions = [];
        foreach ($productIds as $pid) {
            $amounts[$pid] = 0;
            $descriptions[$pid] = '';
        }

        foreach ($promos as $promo) {
            $promoProductIds = $promo->products->pluck('id_products')->toArray();

            foreach ($productIds as $pid) {
                if (!in_array($pid, $promoProductIds, true)) continue;

                $product = Product::find($pid);
                if (!$product) continue;

                if ($promo->discount_type === 'percentage') {
                    $disc = round($product->base_price * $promo->discount_value / 100);
                    $desc = "Diskon {$promo->discount_value}%";
                } else {
                    $disc = (int) $promo->discount_value;
                    $desc = "Pot. Rp" . number_format($promo->discount_value, 0, ',', '.');
                }

                // ambil diskon terbesar + deskripsi promo terbaik
                if ($disc > $amounts[$pid]) {
                    $amounts[$pid] = $disc;
                    $descriptions[$pid] = $desc;
                }
            }
        }

        return ['amounts' => $amounts, 'descriptions' => $descriptions];
    }

    // menghasilkan order number unik. format: PODS-YYYYMMDD-XXXXXX (6 karakter hex uppercase)
    private function generateOrderNumber(): string
    {
        return 'PODS-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }
}