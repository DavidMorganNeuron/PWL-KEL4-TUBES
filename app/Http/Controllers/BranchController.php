<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('manager')
            ->orderBy('name')
            ->get()
            ->map(function ($branch) {
                $ordersToday = Order::where('branch_id', $branch->id_branches)
                    ->whereDate('created_at', today())
                    ->whereIn('status', ['paid', 'cooking', 'completed'])
                    ->count();

                $stockTable = $branch->stockTableName();
                $criticalStock = 0;
                if (Schema::hasTable($stockTable)) {
                    $criticalStock = DB::table($stockTable)
                        ->where('physical_qty', '<', 10)
                        ->count();
                }

                return [
                    'id'             => $branch->id_branches,
                    'name'           => $branch->name,
                    'address'        => $branch->address,
                    'open_time'      => $branch->open_time ? substr($branch->open_time, 0, 5) : null,
                    'close_time'     => $branch->close_time ? substr($branch->close_time, 0, 5) : null,
                    'is_always_open' => $branch->is_always_open,
                    'is_active'      => $branch->is_active,
                    'is_closing'     => $branch->is_closing,
                    'deleted_at'     => $branch->deleted_at,
                    'manager'        => $branch->manager ? [
                        'name'  => $branch->manager->name,
                        'email' => $branch->manager->email,
                    ] : null,
                    'orders_today'   => $ordersToday,
                    'critical_stock' => $criticalStock,
                ];
            });

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $products = Product::where('is_available', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('admin.branches.form', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:100', 'unique:branches,name'],
            'address'          => ['required', 'string', 'max:255'],
            'open_time'        => ['nullable', 'date_format:H:i', 'required_if:is_always_open,0'],
            'close_time'       => ['nullable', 'date_format:H:i', 'after:open_time', 'required_if:is_always_open,0'],
            'is_always_open'   => ['required', 'boolean'],
            'manager_name'     => ['required', 'string', 'max:150'],
            'manager_email'    => ['required', 'email', 'unique:users,email'],
            'manager_password' => ['required', 'string', 'min:8', 'confirmed'],
            'initial_stocks'   => ['required', 'array'],
            'initial_stocks.*' => ['required', 'integer', 'min:1'],
        ]);

        $stockTableName = '';
        $branchId = null;
        $managerId = null;

        try {
            // STEP 1: Generate nama tabel stok dan validasi
            $normalized = strtolower(preg_replace('/[\s.]+/', '_', trim($request->name)));
            $normalized = preg_replace('/[^a-z0-9_]/', '', $normalized);
            $stockTableName = 'stock_branch_' . $normalized;

            if (Schema::hasTable($stockTableName)) {
                return back()->withErrors(['error' => 'Nama cabang sudah ada sebagai tabel stok.'])->withInput();
            }

            // STEP 2: INSERT ke tabel branches
            $branch = Branch::create([
                'name'           => $request->name,
                'address'        => $request->address,
                'open_time'      => $request->is_always_open ? null : $request->open_time,
                'close_time'     => $request->is_always_open ? null : $request->close_time,
                'is_always_open' => $request->is_always_open,
                'is_active'      => true,
                'is_closing'     => false,
            ]);
            $branchId = $branch->id_branches;

            // STEP 3: Buat akun manager
            $manager = User::create([
                'role_id'   => 2,
                'branch_id' => $branchId,
                'name'      => $request->manager_name,
                'email'     => $request->manager_email,
                'password'  => Hash::make($request->manager_password),
            ]);
            $managerId = $manager->id_users;

            // STEP 4: Buat tabel stok
            Schema::create($stockTableName, function (Blueprint $table) use ($stockTableName) {
                $table->bigIncrements('id_' . $stockTableName);
                $table->unsignedBigInteger('product_id');
                $table->integer('physical_qty')->default(0);
                $table->integer('reserved_qty')->default(0);
            });

            // STEP 5: INSERT stok awal
            foreach ($request->initial_stocks as $productId => $qty) {
                $qty = (int) $qty;
                if ($qty > 0) {
                    DB::table($stockTableName)->insert([
                        'product_id'   => $productId,
                        'physical_qty' => $qty,
                        'reserved_qty' => 0,
                    ]);
                }
            }

            // STEP 6: Rebuild global_stocks_view
            $this->rebuildGlobalStocksView();

            // STEP 7: Log sukses
            Log::info("BranchController: cabang baru '{$request->name}' (ID: {$branchId}) berhasil dibuat oleh admin ID " . Auth::id());

        } catch (\Exception $e) {
            // Cleanup manual jika ada step yang gagal
            if ($branchId) {
                Branch::where('id_branches', $branchId)->forceDelete();
            }
            if ($managerId) {
                User::where('id_users', $managerId)->forceDelete();
            }
            if ($stockTableName && Schema::hasTable($stockTableName)) {
                Schema::dropIfExists($stockTableName);
            }

            Log::error("BranchController: gagal membuat cabang - " . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal membuat cabang: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.branches.index')->with('toast', 'Cabang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $branch = Branch::with('manager')->findOrFail($id);
        return view('admin.branches.form', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'address'        => ['required', 'string', 'max:255'],
            'open_time'      => ['nullable', 'date_format:H:i', 'required_if:is_always_open,0'],
            'close_time'     => ['nullable', 'date_format:H:i', 'after:open_time', 'required_if:is_always_open,0'],
            'is_always_open' => ['required', 'boolean'],
            'is_active'      => ['required', 'boolean'],
        ]);

        try {
            $branch->update([
                'address'        => $request->address,
                'open_time'      => $request->is_always_open ? null : $request->open_time,
                'close_time'     => $request->is_always_open ? null : $request->close_time,
                'is_always_open' => $request->is_always_open,
                'is_active'      => $request->is_active,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui cabang: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.branches.index')->with('toast', 'Data cabang diperbarui.');
    }

    // tiga cabang utama yang tidak boleh ditutup
    private const MAIN_BRANCHES = ['Dr. Mansyur', 'Gatot Subroto', 'Jamin Ginting'];

    private function isMainBranch(Branch $branch): bool
    {
        return in_array($branch->name, self::MAIN_BRANCHES, true);
    }

    public function initiateClose($id)
    {
        $branch = Branch::with('manager')->findOrFail($id);

        if (!$branch->is_active) {
            return redirect()->route('admin.branches.index')->with('error', 'Cabang sudah tidak aktif.');
        }

        if ($this->isMainBranch($branch)) {
            return redirect()->route('admin.branches.index')->with('error', 'Cabang utama tidak dapat ditutup.');
        }

        $activeOrders = Order::where('branch_id', $id)
            ->whereIn('status', ['paid', 'cooking'])
            ->count();

        $stockTable = $branch->stockTableName();
        $stockSummary = [];
        if (Schema::hasTable($stockTable)) {
            $stockSummary = DB::table($stockTable . ' as s')
                ->join('products as p', 's.product_id', '=', 'p.id_products')
                ->select('p.name', 's.physical_qty')
                ->where('s.physical_qty', '>', 0)
                ->orderBy('p.name')
                ->get()
                ->map(fn($r) => ['name' => $r->name, 'physical_qty' => (int) $r->physical_qty])
                ->toArray();
        }

        $otherBranches = Branch::where('is_active', true)
            ->where('id_branches', '!=', $id)
            ->orderBy('name')
            ->get(['id_branches', 'name']);

        return view('admin.branches.close', compact('branch', 'activeOrders', 'stockSummary', 'otherBranches'));
    }

    public function executeClose(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        if (!$branch->is_active) {
            return redirect()->route('admin.branches.index')->with('error', 'Cabang sudah tidak aktif.');
        }

        $request->validate([
            'transfer_to_branch_id' => ['nullable', 'integer', 'exists:branches,id_branches'],
            'confirm_name'          => ['required', 'string'],
        ]);

        if ($request->confirm_name !== $branch->name) {
            return back()->withErrors(['confirm_name' => 'Nama cabang yang diketik tidak sesuai.'])->withInput();
        }

        if ($this->isMainBranch($branch)) {
            return redirect()->route('admin.branches.index')->with('error', 'Cabang utama tidak dapat ditutup.');
        }

        $stockTableName = $branch->stockTableName();

        try {
            // STEP 1: Lock cabang agar tidak ada order baru
            Branch::where('id_branches', $id)->update(['is_closing' => true]);

            // STEP 2: Cancel semua order aktif cabang
            Order::where('branch_id', $id)
                ->whereIn('status', ['paid', 'cooking'])
                ->update([
                    'status'        => 'canceled',
                    'cancel_reason' => 'Cabang ditutup permanen.',
                ]);

            // STEP 3: Transfer stok ke cabang lain jika dipilih
            if ($request->transfer_to_branch_id && Schema::hasTable($stockTableName)) {
                $targetBranch = Branch::find($request->transfer_to_branch_id);
                if ($targetBranch) {
                    $targetTable = $targetBranch->stockTableName();
                    $stocks = DB::table($stockTableName)->where('physical_qty', '>', 0)->get();

                    foreach ($stocks as $stock) {
                        $existing = DB::table($targetTable)
                            ->where('product_id', $stock->product_id)
                            ->first();

                        if ($existing) {
                            DB::table($targetTable)
                                ->where('product_id', $stock->product_id)
                                ->increment('physical_qty', $stock->physical_qty);
                        } else {
                            DB::table($targetTable)->insert([
                                'product_id'   => $stock->product_id,
                                'physical_qty' => $stock->physical_qty,
                                'reserved_qty' => 0,
                            ]);
                        }
                    }
                }
            }

            // STEP 4: Hapus stock_log terkait cabang dahulu (FK restrict ke request_log & orders)
            DB::table('stock_log')->where('branch_id', $id)->delete();

            // STEP 5: Hapus request_log terkait cabang (FK restrict ke branches)
            DB::table('request_log')->where('branch_id', $id)->delete();

            // STEP 6: Hapus SEMUA data pesanan cabang (FK restrict ke branches)
            Order::where('branch_id', $id)->delete();

            // STEP 7: Drop tabel stok cabang
            if (Schema::hasTable($stockTableName)) {
                Schema::dropIfExists($stockTableName);
            }

            // STEP 8: Hapus manager cabang
            $managers = User::where('branch_id', $id)->where('role_id', 2)->get();
            foreach ($managers as $mgr) {
                try {
                    $mgr->forceDelete();
                } catch (\Exception $e) {
                    User::where('id_users', $mgr->id_users)->update(['branch_id' => null]);
                }
            }

            // STEP 9: Hapus promo lokal
            DB::table('promos')->where('branch_id', $id)->delete();

            // STEP 10: Hapus permanen cabang dari database
            Branch::where('id_branches', $id)->forceDelete();

            // STEP 11: Rebuild global_stocks_view (hanya cabang aktif tersisa)
            $this->rebuildGlobalStocksView();

            // STEP 12: Log
            Log::warning("BranchController: cabang '{$branch->name}' (ID: {$id}) ditutup oleh admin ID " . Auth::id());

        } catch (\Exception $e) {
            Log::error("BranchController: gagal menutup cabang - " . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal menutup cabang: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.branches.index')->with('success', 'Cabang berhasil ditutup.');
    }

    private function rebuildGlobalStocksView()
    {
        $branches = Branch::where('is_active', true)->get(['name']);
        $unions = [];

        foreach ($branches as $branch) {
            $tableName = 'stock_branch_' . strtolower(preg_replace('/[\s.]+/', '_', trim($branch->name)));
            $tableName = preg_replace('/[^a-z0-9_]/', '', $tableName);
            $safeName = addslashes($branch->name);
            $unions[] = "SELECT '{$safeName}' AS branch_name, product_id, physical_qty, reserved_qty FROM {$tableName}";
        }

        if (empty($unions)) {
            DB::statement("CREATE OR REPLACE VIEW global_stocks_view AS SELECT NULL AS branch_name, NULL AS product_id, NULL AS physical_qty, NULL AS reserved_qty WHERE 1=0");
        } else {
            $sql = "CREATE OR REPLACE VIEW global_stocks_view AS " . implode(" UNION ALL ", $unions);
            DB::statement($sql);
        }
    }
}
