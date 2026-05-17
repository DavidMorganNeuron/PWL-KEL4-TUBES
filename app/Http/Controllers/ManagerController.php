<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Concerns\ResolvesStockTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RequestLog;

/*
    ManagerController
    Mengelola seluruh ruang kerja manager cabang: dashboard, KDS, monitoring stok, laporan penjualan, dan pengajuan restock.
*/
class ManagerController extends Controller
{
    use ResolvesStockTable;
 
    /* Mengembalikan branch_id manager yang sedang login. */
    private function branchId(): int
    {
        return (int) Auth::user()->branch_id;
    }
 
    /*
        Mengembalikan nama tabel stok fisik cabang manager yang sedang login.
        Mendelegasikan resolusi nama ke trait ResolvesStockTable yang sudah ada.
    */
    private function stockTable(): string
    {
        return $this->resolveStockTable(Auth::user()->branch->name);
    }
 
    /* ================================================================
       DASHBOARD
    ================================================================ */
 
    public function dashboard()
    {
        $branchId   = $this->branchId();
        $stockTable = $this->stockTable();
        $today      = now()->toDateString();
 
        /* ringkasan harian: pendapatan, jumlah pesanan, pesanan aktif */
        $todayOrders = Order::where('branch_id', $branchId)
            ->whereDate('created_at', $today)
            ->get();
 
        $revenue      = $todayOrders->whereIn('status', ['completed'])->sum('grand_total');
        $totalOrders  = $todayOrders->count();
        $activeOrders = $todayOrders->whereIn('status', ['paid', 'cooking'])->count();
        $paidCount    = $todayOrders->where('status', 'paid')->count();
        $cookingCount = $todayOrders->where('status', 'cooking')->count();
 
        /* stok kritis: physical_qty di bawah threshold 10 */
        $criticalStocks = DB::table($stockTable)
            ->join('products', 'products.id_products', '=', "{$stockTable}.product_id")
            ->where("{$stockTable}.physical_qty", '<', 10)
            ->select(
                'products.name',
                "{$stockTable}.physical_qty",
                "{$stockTable}.reserved_qty"
            )
            ->orderBy("{$stockTable}.physical_qty")
            ->limit(5)
            ->get();
 
        /* best seller hari ini: top 5 berdasarkan qty terjual */
        $bestSellers = OrderItem::join('orders', 'orders.id_orders', '=', 'order_items.order_id')
            ->join('products', 'products.id_products', '=', 'order_items.product_id')
            ->where('orders.branch_id', $branchId)
            ->whereDate('orders.created_at', $today)
            ->where('orders.status', 'completed')
            ->selectRaw('products.name, SUM(order_items.qty) as total_qty, SUM(order_items.subtotal_price) as total_revenue')
            ->groupBy('products.id_products', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
 
        /* aktivitas terbaru: 5 pesanan terakhir dari semua status */
        $recentOrders = Order::with(['items.product'])
            ->where('branch_id', $branchId)
            ->whereDate('created_at', $today)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
 
        /*
            Data grafik: 3 mode periode — harian (30 hari), mingguan (12 minggu), bulanan (12 bulan).
            Pendapatan bersih = revenue completed - nilai_waste.
        */
        $chartData = $this->buildChartData($branchId);
 
        return view('manager.dashboard', compact(
            'revenue',
            'totalOrders',
            'activeOrders',
            'paidCount',
            'cookingCount',
            'criticalStocks',
            'bestSellers',
            'recentOrders',
            'chartData'
        ));
    }
 
    /* Membangun dataset grafik untuk 3 mode periode. */
    private function buildChartData(int $branchId): array
    {
        /* HARIAN: 30 hari terakhir */
        $dailyRevenue = Order::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as period, SUM(grand_total) as revenue')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('period')
            ->pluck('revenue', 'period');
 
        $dailyWaste = Order::where('branch_id', $branchId)
            ->where('status', 'canceled')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->whereExists(function ($q) {
                // hanya cancel yang sudah masuk dapur (ada entry waste di stock_log)
                $q->select(DB::raw(1))
                  ->from('stock_log')
                  ->whereColumn('stock_log.order_id', 'orders.id_orders')
                  ->where('stock_log.activity_type', 'waste');
            })
            ->selectRaw('DATE(created_at) as period, SUM(grand_total) as waste')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('period')
            ->pluck('waste', 'period');
 
        $dailyLabels = [];
        $dailyNet    = [];
        for ($i = 29; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = now()->subDays($i)->format('d/m');
            $rev           = (float) ($dailyRevenue[$date] ?? 0);
            $wst           = (float) ($dailyWaste[$date]   ?? 0);
            $dailyNet[]    = max(0, $rev - $wst);
        }
 
        /* MINGGUAN: 12 minggu terakhir */
        $weeklyRevenue = Order::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subWeeks(11)->startOfWeek())
            ->selectRaw('YEARWEEK(created_at, 1) as period, SUM(grand_total) as revenue')
            ->groupByRaw('YEARWEEK(created_at, 1)')
            ->orderBy('period')
            ->pluck('revenue', 'period');
 
        $weeklyWaste = Order::where('branch_id', $branchId)
            ->where('status', 'canceled')
            ->where('created_at', '>=', now()->subWeeks(11)->startOfWeek())
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('stock_log')
                  ->whereColumn('stock_log.order_id', 'orders.id_orders')
                  ->where('stock_log.activity_type', 'waste');
            })
            ->selectRaw('YEARWEEK(created_at, 1) as period, SUM(grand_total) as waste')
            ->groupByRaw('YEARWEEK(created_at, 1)')
            ->orderBy('period')
            ->pluck('waste', 'period');
 
        $weeklyLabels = [];
        $weeklyNet    = [];
        for ($i = 11; $i >= 0; $i--) {
            $weekStart      = now()->subWeeks($i)->startOfWeek();
            $key            = $weekStart->format('oW'); // ISO year+week
            $weeklyLabels[] = 'W' . $weekStart->format('W');
            $rev            = (float) ($weeklyRevenue[$key] ?? 0);
            $wst            = (float) ($weeklyWaste[$key]   ?? 0);
            $weeklyNet[]    = max(0, $rev - $wst);
        }
 
        /* BULANAN: 12 bulan terakhir */
        $monthlyRevenue = Order::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, SUM(grand_total) as revenue")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('period')
            ->pluck('revenue', 'period');
 
        $monthlyWaste = Order::where('branch_id', $branchId)
            ->where('status', 'canceled')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('stock_log')
                  ->whereColumn('stock_log.order_id', 'orders.id_orders')
                  ->where('stock_log.activity_type', 'waste');
            })
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, SUM(grand_total) as waste")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('period')
            ->pluck('waste', 'period');
 
        $monthlyLabels = [];
        $monthlyNet    = [];
        for ($i = 11; $i >= 0; $i--) {
            $month          = now()->subMonths($i);
            $key            = $month->format('Y-m');
            $monthlyLabels[] = $month->translatedFormat('M Y');
            $rev            = (float) ($monthlyRevenue[$key] ?? 0);
            $wst            = (float) ($monthlyWaste[$key]   ?? 0);
            $monthlyNet[]   = max(0, $rev - $wst);
        }
 
        return [
            'daily'   => ['labels' => $dailyLabels,   'data' => $dailyNet],
            'weekly'  => ['labels' => $weeklyLabels,  'data' => $weeklyNet],
            'monthly' => ['labels' => $monthlyLabels, 'data' => $monthlyNet],
        ];
    }
 
    /* ================================================================
       KITCHEN DISPLAY SYSTEM (KDS)
    ================================================================ */
 
    public function kds()
    {
        $branchId = $this->branchId();
 
        /* Ambil pesanan aktif (paid + cooking) dan selesai hari ini (completed). */
        $orders = Order::with(['items.product', 'user'])
            ->where('branch_id', $branchId)
            ->whereIn('status', ['paid', 'cooking', 'completed'])
            ->whereDate('created_at', now()->toDateString())
            ->orderByRaw("FIELD(status, 'paid', 'cooking', 'completed')")
            ->orderBy('created_at')
            ->get();
 
        return view('manager.kds', compact('orders'));
    }
 
    /*
        Mengubah status pesanan dari paid → cooking.
        Hanya pesanan milik cabang manager sendiri yang bisa diubah.
    */
    public function cookOrder(int $id)
    {
        $order = Order::where('id_orders', $id)
            ->where('branch_id', $this->branchId())
            ->where('status', 'paid')
            ->firstOrFail();
 
        $order->update(['status' => 'cooking']);
 
        Log::info("ManagerController: Order #{$order->order_number} → cooking oleh manager " . Auth::id());
 
        return back()->with('toast', "Pesanan #{$order->order_number} sedang dimasak.");
    }
 
    /* Mengubah status pesanan dari cooking → completed. */
    public function doneOrder(int $id)
    {
        $order = Order::where('id_orders', $id)
            ->where('branch_id', $this->branchId())
            ->where('status', 'cooking')
            ->firstOrFail();
 
        $order->update(['status' => 'completed']);
 
        Log::info("ManagerController: Order #{$order->order_number} → completed oleh manager " . Auth::id());
 
        return back()->with('toast', "Pesanan #{$order->order_number} selesai dan siap diambil.");
    }
 
    /*
        Pembatalan darurat: mengubah status pesanan ke canceled.
        Alasan wajib diisi. Stok fisik TIDAK dikembalikan karena sudah dipotong
        saat payment dan dicatat sebagai 'waste' di stock_log.
    */
    public function cancelOrder(Request $request, int $id)
    {
        $request->validate([
            'cancel_reason' => ['required', 'string', 'max:500'],
        ]);
 
        $order = Order::with('items')
            ->where('id_orders', $id)
            ->where('branch_id', $this->branchId())
            ->whereIn('status', ['paid', 'cooking'])
            ->firstOrFail();
 
        $branchId   = $this->branchId();
        $stockTable = $this->stockTable();
 
        $order->update([
            'status'        => 'canceled',
            'cancel_reason' => $request->cancel_reason,
        ]);
 
        /* Catat setiap item yang batal sebagai 'waste' di stock_log. */
        foreach ($order->items as $item) {
            DB::table('stock_log')->insert([
                'branch_id'       => $branchId,
                'product_id'      => $item->product_id,
                'user_id'         => Auth::id(),
                'order_id'        => $order->id_orders,
                'request_id'      => null,
                'activity_type'   => 'waste',
                'quantity_change' => 0,
                'created_at'      => now(),
            ]);
        }
 
        Log::warning("ManagerController: Order #{$order->order_number} dibatalkan oleh manager " . Auth::id() . " — alasan: {$request->cancel_reason}");
 
        return back()->with('toast', "Pesanan #{$order->order_number} dibatalkan.");
    }
 
    /* ================================================================
       MONITORING STOK LOKAL
    ================================================================ */
 
    public function stock()
    {
        $stockTable = $this->stockTable();
 
        /* ambil seluruh stok cabang ini */
        $stocks = DB::table($stockTable)
            ->join('products', 'products.id_products', '=', "{$stockTable}.product_id")
            ->join('categories', 'categories.id_categories', '=', 'products.category_id')
            ->select(
                "{$stockTable}.product_id",
                'products.name as product_name',
                'categories.name as category_name',
                "{$stockTable}.physical_qty",
                "{$stockTable}.reserved_qty",
                DB::raw("({$stockTable}.physical_qty - {$stockTable}.reserved_qty) as available_qty")
            )
            ->orderBy('categories.name')
            ->orderBy('products.name')
            ->get();
 
        return view('manager.stock', compact('stocks'));
    }
 
    /* ================================================================
       LAPORAN PENJUALAN
    ================================================================ */
 
    public function report(Request $request)
    {
        $branchId = $this->branchId();
 
        /* bulan berjalan */
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to',   now()->toDateString());
 
        /* validasi agar range tanggal tidak terbalik */
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }
 
        /* statistik ringkasan periode */
        $periodOrders = Order::where('branch_id', $branchId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->get();
 
        $totalRevenue     = $periodOrders->where('status', 'completed')->sum('grand_total');
        $totalCompleted   = $periodOrders->where('status', 'completed')->count();
        $totalCanceled    = $periodOrders->where('status', 'canceled')->count();
        $avgPerOrder      = $totalCompleted > 0 ? round($totalRevenue / $totalCompleted) : 0;
 
        /* daftar transaksi: load relasi items + user untuk tampilan tabel */
        $transactions = Order::with(['items.product', 'user'])
            ->where('branch_id', $branchId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->orderByDesc('created_at')
            ->paginate(20);
 
        /* top seller periode ini */
        $topSellers = OrderItem::join('orders', 'orders.id_orders', '=', 'order_items.order_id')
            ->join('products', 'products.id_products', '=', 'order_items.product_id')
            ->where('orders.branch_id', $branchId)
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$from, $to])
            ->where('orders.status', 'completed')
            ->selectRaw('products.name, SUM(order_items.qty) as total_qty, SUM(order_items.subtotal_price) as total_revenue')
            ->groupBy('products.id_products', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
 
        return view('manager.report', compact(
            'from',
            'to',
            'totalRevenue',
            'totalCompleted',
            'totalCanceled',
            'avgPerOrder',
            'transactions',
            'topSellers'
        ));
    }
 
    /* ================================================================
       PENGAJUAN RESTOCK
    ================================================================ */
 
    /* Menampilkan form pengajuan restock. */
    public function requestForm(Request $request)
    {
        $stockTable  = $this->stockTable();
        $branchId    = $this->branchId();
 
        /* daftar produk berikut stok saat ini */
        $products = DB::table($stockTable)
            ->join('products', 'products.id_products', '=', "{$stockTable}.product_id")
            ->join('categories', 'categories.id_categories', '=', 'products.category_id')
            ->select(
                'products.id_products',
                'products.name as product_name',
                'categories.name as category_name',
                "{$stockTable}.physical_qty",
                "{$stockTable}.reserved_qty"
            )
            ->orderBy('categories.name')
            ->orderBy('products.name')
            ->get();
 
        /* riwayat pengajuan cabang ini: 10 terbaru */
        $requestHistory = RequestLog::with('product')
            ->forBranch($branchId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
 
        $preselectedId = $request->query('product_id');
 
        return view('manager.request_form', compact('products', 'requestHistory', 'preselectedId'));
    }
 
    /* Menyimpan pengajuan restock baru ke tabel request_log. */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'product_id'    => ['required', 'integer', 'exists:products,id_products'],
            'requested_qty' => ['required', 'integer', 'min:1', 'max:9999'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ]);
 
        $branchId = $this->branchId();
 
        /* cegah duplikasi: jika sudah ada pengajuan pending untuk produk + cabang yang sama */
        $alreadyPending = RequestLog::where('branch_id', $branchId)
            ->where('product_id', $request->product_id)
            ->where('status', 'pending')
            ->exists();
 
        if ($alreadyPending) {
            return back()
                ->withInput()
                ->withErrors(['product_id' => 'Produk ini sudah memiliki pengajuan restock yang sedang menunggu persetujuan admin.']);
        }
 
        RequestLog::create([
            'branch_id'     => $branchId,
            'product_id'    => $request->product_id,
            'manager_id'    => Auth::id(),
            'admin_id'      => null,
            'requested_qty' => $request->requested_qty,
            'notes'         => $request->notes,
            'status'        => 'pending',
        ]);
 
        Log::info("ManagerController: Manager " . Auth::id() . " mengajukan restock product_id={$request->product_id} qty={$request->requested_qty} untuk branch_id={$branchId}");
 
        return redirect()->route('manager.request_form')
            ->with('toast', 'Pengajuan restock berhasil dikirim ke Admin Pusat.');
    }
}