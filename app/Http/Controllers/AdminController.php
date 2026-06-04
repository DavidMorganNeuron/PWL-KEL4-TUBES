<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RequestLog;
use App\Models\User;


// AdminController - dashboard eksekutif + laporan penjualan & aset stok
class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRevenue     = Order::where('status', 'completed')->sum('grand_total');
        $totalOrders      = Order::where('status', 'completed')->count();

        $activeProducts   = Product::where('is_available', true)->count();
        $pendingRequests  = RequestLog::where('status', 'pending')->count();

        $branches = Branch::orderBy('name')->get();
        $branchRevenue = [];
        $totalRev       = 0;

        foreach ($branches as $branch) {
            $rev  = Order::where('branch_id', $branch->id_branches)
                        ->where('status', 'completed')
                        ->sum('grand_total');
            $ord  = Order::where('branch_id', $branch->id_branches)
                        ->where('status', 'completed')
                        ->count();
            $totalRev += $rev;

            $branchRevenue[] = [
                'name'   => $branch->name,
                'revenue'=> $rev,
                'orders' => $ord,
                'pct'    => 0,
            ];
        }

        foreach ($branchRevenue as &$br) {
            $br['pct'] = $totalRev > 0 ? round(($br['revenue'] / $totalRev) * 100) : 0;
        }

        $topSellersOverall = OrderItem::select('product_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(subtotal_price) as total_revenue'))
            ->whereHas('order', fn($q) => $q->where('status', 'completed'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product')
            ->get()
            ->map(fn($item, $idx) => [
                'rank'    => $idx + 1,
                'name'    => $item->product?->name ?? 'Unknown',
                'qty'     => (int) $item->total_qty,
                'revenue' => (float) $item->total_revenue,
            ]);

        $topSellerPerBranch = [];
        foreach ($branches as $branch) {
            $items = OrderItem::select('product_id',
                    DB::raw('SUM(qty) as total_qty'))
                ->whereHas('order', fn($q) => $q
                    ->where('branch_id', $branch->id_branches)
                    ->where('status', 'completed'))
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->limit(3)
                ->with('product')
                ->get()
                ->map(fn($item) => [
                    'name' => $item->product?->name ?? 'Unknown',
                    'qty'  => (int) $item->total_qty,
                ]);

            $topSellerPerBranch[$branch->name] = $items->toArray();
        }

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'activeProducts', 'pendingRequests',
            'branchRevenue', 'topSellersOverall', 'topSellerPerBranch',
        ));
    }

    public function sales(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $baseQuery = fn($q) => $q->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);

        $totalRevenue    = Order::where('status', 'completed')->where($baseQuery)->sum('grand_total');
        $totalOrders     = Order::where('status', 'completed')->where($baseQuery)->count();
        $totalOrdersAll  = Order::whereIn('status', ['paid','cooking','completed','canceled'])->where($baseQuery)->count();
        $totalDiscount   = Order::where('status', 'completed')->where($baseQuery)->sum('total_discount');
        $avgPerTransaction = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

        $branches = Branch::orderBy('name')->get();
        $perBranch = [];
        $grandRev = 0;

        foreach ($branches as $branch) {
            $rev  = Order::where('branch_id', $branch->id_branches)
                        ->where('status', 'completed')
                        ->where($baseQuery)->sum('grand_total');
            $compl = Order::where('branch_id', $branch->id_branches)
                        ->where('status', 'completed')
                        ->where($baseQuery)->count();
            $cancel = Order::where('branch_id', $branch->id_branches)
                        ->where('status', 'canceled')
                        ->where($baseQuery)->count();
            $disc  = Order::where('branch_id', $branch->id_branches)
                        ->where('status', 'completed')
                        ->where($baseQuery)->sum('total_discount');
            $net   = $rev - $disc;
            $grandRev += $rev;

            $perBranch[] = [
                'branch'    => $branch->name,
                'completed' => $compl,
                'canceled'  => $cancel,
                'revenue'   => $rev,
                'discount'  => $disc,
                'net'       => $net,
            ];
        }

        $topSellersPerBranch = [];
        foreach ($branches as $branch) {
            $items = OrderItem::select('product_id',
                    DB::raw('SUM(qty) as total_qty'),
                    DB::raw('SUM(subtotal_price) as total_revenue'))
                ->whereHas('order', fn($q) => $q
                    ->where('branch_id', $branch->id_branches)
                    ->where('status', 'completed')
                    ->where($baseQuery))
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->limit(3)
                ->with('product')
                ->get()
                ->map(fn($item) => [
                    'name'    => $item->product?->name ?? 'Unknown',
                    'qty'     => (int) $item->total_qty,
                    'revenue' => (float) $item->total_revenue,
                ]);

            $topSellersPerBranch[$branch->name] = $items->toArray();
        }

        $recentTransactions = Order::whereIn('status', ['paid','cooking','completed','canceled'])
            ->where($baseQuery)
            ->with(['branch', 'user'])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn($order) => [
                'order_number' => $order->order_number,
                'branch'       => $order->branch?->name ?? '-',
                'customer'     => $order->user?->name ?? '-',
                'status'       => $order->status,
                'grand_total'  => (float) $order->grand_total,
                'date'         => $order->created_at->format('d M Y, H:i'),
            ]);

        return view('admin.reports.sales', compact(
            'totalRevenue', 'totalOrders', 'totalOrdersAll', 'totalDiscount',
            'avgPerTransaction', 'perBranch', 'grandRev',
            'topSellersPerBranch', 'recentTransactions', 'from', 'to',
        ));
    }

    public function assets()
    {
        $rows = DB::table('global_stocks_view')
            ->join('products', 'global_stocks_view.product_id', '=', 'products.id_products')
            ->join('categories', 'products.category_id', '=', 'categories.id_categories')
            ->select(
                'global_stocks_view.branch_name as branch',
                'products.name as product',
                'categories.name as category',
                'global_stocks_view.physical_qty',
                'global_stocks_view.reserved_qty',
            )
            ->orderBy('global_stocks_view.branch_name')
            ->orderBy('categories.name')
            ->orderBy('products.name')
            ->get()
            ->map(fn($r) => [
                'branch'       => $r->branch,
                'product'      => $r->product,
                'category'     => $r->category,
                'physical_qty' => (int) $r->physical_qty,
                'reserved_qty' => (int) $r->reserved_qty,
            ])
            ->toArray();

        $globalStocks = $rows;
        $threshold = 10;

        $totalPhysical  = array_sum(array_column($globalStocks, 'physical_qty'));
        $totalReserved  = array_sum(array_column($globalStocks, 'reserved_qty'));
        $totalAvailable = $totalPhysical - $totalReserved;
        $criticalCount  = count(array_filter($globalStocks, fn($s) => $s['physical_qty'] < $threshold));

        $byBranch = [];
        foreach ($globalStocks as $s) {
            $byBranch[$s['branch']][] = $s;
        }

        $categories = array_unique(array_column($globalStocks, 'category'));
        sort($categories);

        return view('admin.reports.assets', compact(
            'globalStocks', 'threshold', 'totalPhysical', 'totalReserved',
            'totalAvailable', 'criticalCount', 'byBranch', 'categories',
        ));
    }

    public function managers()
    {
        $managers = User::where('role_id', 2)
            ->with('branch')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                $branch = $user->branch;
                $ordersToday = Order::where('branch_id', $branch?->id_branches)
                    ->whereDate('created_at', today())
                    ->count();
                $revenueMtd  = Order::where('branch_id', $branch?->id_branches)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', now()->month)
                    ->sum('grand_total');

                return [
                    'id'           => $user->id_users,
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'branch'       => $branch?->name ?? '-',
                    'address'      => $branch?->address ?? '-',
                    'is_open'      => $branch?->isOpen() ?? false,
                    'open_time'    => $branch?->open_time ? substr($branch->open_time, 0, 5) : null,
                    'close_time'   => $branch?->close_time ? substr($branch->close_time, 0, 5) : null,
                    'always_open'  => $branch?->is_always_open ?? false,
                    'joined_at'    => $user->created_at?->format('Y-m-d') ?? now()->format('Y-m-d'),
                    'orders_today' => $ordersToday,
                    'revenue_mtd'  => $revenueMtd,
                ];
            });

        return view('admin.managers.manager', compact('managers'));
    }

    public function requests()
    {
        $requests = RequestLog::with(['branch', 'product', 'manager'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($r) => [
                'id'            => $r->id_request_log,
                'branch'        => $r->branch?->name ?? '-',
                'branch_id'     => $r->branch_id,
                'manager'       => $r->manager?->name ?? '-',
                'product'       => $r->product?->name ?? '-',
                'product_id'    => $r->product_id,
                'requested_qty' => $r->requested_qty,
                'status'        => $r->status,
                'notes'         => $r->notes,
                'created_at'    => $r->created_at?->format('Y-m-d H:i') ?? '-',
            ]);

        return view('admin.requests.request', compact('requests'));
    }

    public function showRequest($id)
    {
        $r = RequestLog::with(['branch', 'product', 'manager'])->findOrFail($id);

        $request = [
            'id'            => $r->id_request_log,
            'branch'        => $r->branch?->name ?? '-',
            'branch_id'     => $r->branch_id,
            'manager'       => $r->manager?->name ?? '-',
            'product'       => $r->product?->name ?? '-',
            'product_id'    => $r->product_id,
            'requested_qty' => $r->requested_qty,
            'status'        => $r->status,
            'notes'         => $r->notes,
            'created_at'    => $r->created_at?->format('Y-m-d H:i') ?? '-',
        ];

        $currentStock = $this->resolveProductStock($r->branch_id, $r->product_id);

        return view('admin.requests.show', compact('request', 'currentStock'));
    }

    public function approveRequest($id)
    {
        $r = RequestLog::with('branch')->findOrFail($id);

        if ($r->status !== 'pending') {
            return redirect()->route('admin.requests.index')
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $r->update(['status' => 'approved', 'admin_id' => auth()->id()]);

        $stockTable = $this->resolveStockTable($r->branch->name);

        DB::table($stockTable)
            ->where('product_id', $r->product_id)
            ->increment('physical_qty', $r->requested_qty);

        DB::table('stock_log')->insert([
            'branch_id'      => $r->branch_id,
            'product_id'     => $r->product_id,
            'user_id'        => auth()->id(),
            'order_id'       => null,
            'request_id'     => $r->id_request_log,
            'activity_type'  => 'restock_approved',
            'quantity_change'=> $r->requested_qty,
            'created_at'     => now(),
        ]);

        return redirect()->route('admin.requests.index')
            ->with('toast', "Restock {$r->product?->name} di {$r->branch?->name} berhasil disetujui. Stok bertambah {$r->requested_qty} unit.");
    }

    public function rejectRequest(Request $request, $id)
    {
        $r = RequestLog::findOrFail($id);

        if ($r->status !== 'pending') {
            return redirect()->route('admin.requests.index')
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate(['notes' => ['nullable', 'string', 'max:500']]);

        $r->update([
            'status'   => 'rejected',
            'admin_id' => auth()->id(),
            'notes'    => $request->notes,
        ]);

        return redirect()->route('admin.requests.index')
            ->with('toast', 'Pengajuan restock ditolak.');
    }

    private function resolveStockTable(string $branchName): string
    {
        $map = [
            'Dr. Mansyur'   => 'stock_branch_dr_mansyur',
            'Jamin Ginting' => 'stock_branch_jamin_ginting',
            'Gatot Subroto' => 'stock_branch_gatot_subroto',
        ];
        return $map[$branchName] ?? 'stock_branch_dr_mansyur';
    }

    private function resolveProductStock(int $branchId, int $productId): array
    {
        $branch = \App\Models\Branch::find($branchId);
        if (!$branch) {
            return ['physical_qty' => 0, 'reserved_qty' => 0];
        }

        $table = $this->resolveStockTable($branch->name);
        $row = DB::table($table)->where('product_id', $productId)->first();

        return [
            'physical_qty'  => $row->physical_qty ?? 0,
            'reserved_qty'  => $row->reserved_qty ?? 0,
        ];
    }
}
