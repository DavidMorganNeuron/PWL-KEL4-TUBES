<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        // ini supaya nonaktifkan foreign key sementara untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('stock_log')->truncate();
        DB::table('payments')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('request_log')->truncate();
        DB::table('promo_products')->truncate();
        DB::table('promos')->truncate();
        DB::table('stock_branch_dr_mansyur')->truncate();
        DB::table('stock_branch_jamin_ginting')->truncate();
        DB::table('stock_branch_gatot_subroto')->truncate();
        DB::table('products')->truncate();
        DB::table('users')->where('role_id', 3)->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // produk
        $products = [
            // Coffee
            [1, 'Ristretto Bianco',               63000, 50],
            [1, 'Americano',                      45000, 10],
            [1, 'Pumpkin Spice Latte',            65000, 50],
            [1, 'Spanish Aren Latte',             63000,  5],
            [1, 'Caramel Macchiato',              65000, 10],
            [1, 'Cold Brew Coffee',               50000,  5],
            [1, 'Salted Caramel Latte',           65000, 10],
            [1, 'Asian Dolce',                    65000,  5],
            [1, 'Flat White',                     60000,  5],
            [1, 'Cortado',                        70000,  5],
            [1, 'White Almond Milk Cappuccino',   71000,  5],
            [1, 'Butterscotch Sea Salt Latte',    62000, 10],
            [1, 'Praline Latte',                  62000, 10],
            [1, 'Mocha Latte',                    62000,  5],
            [1, 'Espresso',                       20000, 30],
            // Non-Coffee
            [2, 'Ceremonial Matcha',              75000, 30],
            [2, 'Signature Chocolate',            70000, 30],
            [2, 'Chai Tea Latte',                 62000, 10],
            [2, 'Deep Roast Oolong Milk Tea',     55000, 10],
            [2, 'Deep Roast Oolong Tea',          50000,  5],
            [2, 'Deep Roast Black Tea',           50000,  5],
            [2, 'Deep Roast Black Milk Tea',      55000, 10],
            [2, 'English Breakfast Tea',          50000, 20],
            [2, 'Signature Indonesian Teh Tarik', 55000, 10],
            // Food
            [3, 'Signature Beef Lasagna',         70000, 20],
            [3, 'Mac & Cheese',                   56000, 10],
            [3, 'Cheese Cake',                    70000, 10],
            [3, 'Espresso Brownies',              55000, 10],
            [3, 'Beef & Fries',                   55000, 20],
            [3, 'Chocolate Croissant',            55000, 20],
            [3, 'Cheesy Croissant',               55000, 20],
            [3, 'Butter Croissant',               50000, 20],
        ];

        $prices = [];
        foreach ($products as $i => $p) {
            $id = DB::table('products')->insertGetId([
                'category_id'  => $p[0],
                'name'         => $p[1],
                'image_url'    => null,
                'base_price'   => $p[2],
                'is_available' => true,
            ]);
            $prices[$id] = $p[2];
        }

        // Customer
        $customers = [
            [5,  'David Morgan',        'david@gmail.com'],
            [6,  'Salvario Demenico',   'salvario@gmail.com'],
            [7,  'Danielle Sugeharto',  'danielle@gmail.com'],
            [8,  'Hannan Rava',         'hannan@gmail.com'],
            [9,  'Nathan Charlie',      'nathan@gmail.com'],
            [10, 'Audrey Syantika',     'audrey@gmail.com'],
            [11, 'Timotius Gultom',     'timotius@gmail.com'],
            [12, 'Sarah Fivemin',       'sarah@gmail.com'],
            [13, 'Marco Sidauruk',      'marco@gmail.com'],
            [14, 'Leo Sipahutar',       'leo@gmail.com'],
        ];

        $customerIds = [];
        foreach ($customers as $c) {
            DB::table('users')->insert([
                'id_users'   => $c[0],
                'role_id'    => 3,
                'branch_id'  => null,
                'name'       => $c[1],
                'email'      => $c[2],
                'password'   => Hash::make('customer123'),
                'created_at' => now(),
            ]);
            $customerIds[] = $c[0];
        }

        // stok
        $stockTables = [
            3 => 'stock_branch_gatot_subroto',
            1 => 'stock_branch_dr_mansyur',
            2 => 'stock_branch_jamin_ginting',
        ];

        $stockDistribution = [
             1 => [20, 15, 15],
             2 => [ 4,  3,  3],
             3 => [20, 15, 15],
             4 => [ 2,  2,  1],
             5 => [ 4,  3,  3],
             6 => [ 2,  2,  1],
             7 => [ 4,  3,  3],
             8 => [ 2,  2,  1],
             9 => [ 2,  2,  1],
            10 => [ 2,  2,  1],
            11 => [ 2,  2,  1],
            12 => [ 4,  3,  3],
            13 => [ 4,  3,  3],
            14 => [ 2,  2,  1],
            15 => [12, 10,  8],
            16 => [12, 10,  8],
            17 => [12, 10,  8],
            18 => [ 4,  3,  3],
            19 => [ 4,  3,  3],
            20 => [ 2,  2,  1],
            21 => [ 2,  2,  1],
            22 => [ 4,  3,  3],
            23 => [ 8,  7,  5],
            24 => [ 4,  3,  3],
            25 => [ 8,  7,  5],
            26 => [ 4,  3,  3],
            27 => [ 4,  3,  3],
            28 => [ 4,  3,  3],
            29 => [ 8,  7,  5],
            30 => [ 8,  7,  5],
            31 => [ 8,  7,  5],
            32 => [ 8,  7,  5],
        ];

        $branchStockMap = [
            1 => 0, 2 => 1, 3 => 2,
        ];

        foreach ($stockTables as $branchId => $table) {
            $col = $branchStockMap[$branchId];
            for ($pid = 1; $pid <= 32; $pid++) {
                $physical = $stockDistribution[$pid][$col];
                $reserved = 0; // semua completed, tidak ada pemesanan aktif
                DB::table($table)->insert([
                    'product_id'   => $pid,
                    'physical_qty' => $physical,
                    'reserved_qty' => $reserved,
                ]);
            }
        }

        // promo 11
        $promos = [
            // dr. mansyur
            [1, 'Mansyur Morning Deal',     'percentage', 15, [3, 15, 23, 27]],
            [1, 'Mansyur Coffee Lover',     'nominal',  5000, [1, 5, 9, 13]],
            [1, 'Mansyur Student Hour',     'percentage', 20, [16, 18, 22, 24]],
            // jamin ginting
            [2, 'Ginting Combo Hemat',       'nominal',  7000, [2, 6, 25, 30]],
            [2, 'Ginting Afternoon Tea',     'percentage', 10, [19, 23, 24, 31]],
            [2, 'Ginting Weekend Special',   'nominal',  8000, [7, 12, 26, 32]],
            // gatot subroto
            [3, 'Subroto Breakfast Set',     'percentage', 12, [4, 8, 29, 30]],
            [3, 'Subroto Signature',         'nominal', 10000, [10, 11, 14, 17]],
            [3, 'Subroto Happy Hour',        'percentage', 25, [1, 15, 20, 21, 28]],
            // global
            [null, 'Nasional Spesial',       'percentage', 20, [3, 5, 7, 16, 25]],
            [null, 'PayDay Fest',            'nominal', 15000, [1, 9, 13, 17, 22, 29]],
        ];

        $promoIds = [];
        foreach ($promos as $p) {
            $pid = DB::table('promos')->insertGetId([
                'branch_id'      => $p[0],
                'name'           => $p[1],
                'discount_type'  => $p[2],
                'discount_value' => $p[3],
                'start_date'     => Carbon::parse('2026-04-01'),
                'end_date'       => Carbon::parse('2026-06-10'),
                'is_active'      => true,
            ]);
            $promoIds[] = $pid;

            foreach ($p[4] as $prodId) {
                DB::table('promo_products')->insert([
                    'promo_id'   => $pid,
                    'product_id' => $prodId,
                ]);
            }
        }

        // order
        $start = Carbon::parse('2026-04-01');
        $end   = Carbon::now();

        $orderTemplates = [
            [1 => 1, 15 => 1],
            [3 => 2],
            [2 => 1, 23 => 1, 25 => 1],
            [5 => 1, 18 => 1],
            [7 => 1, 26 => 1],
            [15 => 1, 16 => 1, 27 => 1],
            [9 => 1, 30 => 1],
            [1 => 2, 32 => 1],
            [3 => 1, 17 => 1, 28 => 1],
            [10 => 1, 22 => 1],
            [6 => 1, 29 => 1],
            [4 => 1, 31 => 1],
            [13 => 1, 19 => 1],
            [8 => 1, 20 => 1, 24 => 1],
            [11 => 1, 12 => 1],
            [2 => 1, 14 => 1, 21 => 1],
        ];

        $orderIds = [];
        $orderDataForLog = [];
        $orderIdx = 0;

        $totalDays = (int) $start->diffInDays($end);

        for ($branchId = 1; $branchId <= 3; $branchId++) {
            for ($localIdx = 0; $localIdx < 100; $localIdx++) {
                $orderIdx++;

                $dayOffset = (int) round($localIdx * $totalDays / 100);
                $date = $start->copy()->addDays($dayOffset);
                if ($date->gt($end)) $date = $end->copy();

                $tmpl = $orderTemplates[$orderIdx % count($orderTemplates)];
                $userId = $customerIds[$orderIdx % count($customerIds)];

                $items = [];
                $subtotal = 0;
                foreach ($tmpl as $pid => $qty) {
                    $price = $prices[$pid];
                    $lineTotal = $price * $qty;
                    $subtotal += $lineTotal;
                    $items[] = [
                        'product_id'      => $pid,
                        'qty'             => $qty,
                        'base_price'      => $price,
                        'discount_amount' => 0,
                        'subtotal_price'  => $lineTotal,
                    ];
                }

                $appliedPromoId = null;
                $totalDiscount = 0;
                if ($orderIdx % 5 === 0) {
                    $branchPromos = DB::table('promos')
                        ->where(function ($q) use ($branchId) {
                            $q->where('branch_id', $branchId)->orWhereNull('branch_id');
                        })->where('is_active', true)->pluck('id_promos')->toArray();
                    if (!empty($branchPromos)) {
                        $appliedPromoId = $branchPromos[$orderIdx % count($branchPromos)];
                        $promo = DB::table('promos')->where('id_promos', $appliedPromoId)->first();
                        if ($promo->discount_type === 'percentage') {
                            $totalDiscount = (int) round($subtotal * $promo->discount_value / 100);
                        } else {
                            $totalDiscount = min((int) $promo->discount_value, $subtotal);
                        }
                    }
                }

                $grandTotal = $subtotal - $totalDiscount;
                $orderNumber = 'PODS-' . $date->format('Ymd') . '-' . chr(64 + $branchId) . str_pad($localIdx + 1, 4, '0', STR_PAD_LEFT);

                $oid = DB::table('orders')->insertGetId([
                    'branch_id'      => $branchId,
                    'user_id'        => $userId,
                    'order_number'   => $orderNumber,
                    'promo_id'       => $appliedPromoId,
                    'subtotal'       => $subtotal,
                    'total_discount' => $totalDiscount,
                    'grand_total'    => $grandTotal,
                    'status'         => 'completed',
                    'cancel_reason'  => null,
                    'created_at'     => $date->setTime(8 + ($orderIdx % 12), ($orderIdx * 7) % 60),
                    'updated_at'     => $date->setTime(8 + ($orderIdx % 12), ($orderIdx * 7) % 60),
                ]);
                $orderIds[] = $oid;
                $orderDataForLog[] = [
                    'id' => $oid,
                    'branch_id' => $branchId,
                    'user_id' => $userId,
                    'created_at' => $date,
                ];

                // insert item pesanan
                foreach ($items as $it) {
                    DB::table('order_items')->insert([
                        'order_id'        => $oid,
                        'product_id'      => $it['product_id'],
                        'qty'             => $it['qty'],
                        'base_price'      => $it['base_price'],
                        'discount_amount' => $it['discount_amount'],
                        'subtotal_price'  => $it['subtotal_price'],
                    ]);
                }

                DB::table('payments')->insert([
                    'order_id' => $oid,
                    'method'   => $orderIdx % 3 === 0 ? 'E_Wallet' : 'QRIS',
                    'status'   => 'success',
                    'paid_at'  => $date,
                ]);
            }
        }

        // request_log
        $requestLogs = [
            // dr. mansyur
            [1,  2, 2, 1, 20, 'approved',  10, 8],
            [1,  8, 2, 1, 10, 'rejected',   5, 2],
            [1,  7, 2, null, 30, 'pending',  1, 1],
            [1,  1, 2, null, 25, 'pending',  0, 0],
            // jamin ginting
            [2,  5, 3, 1, 15, 'approved',   7, 5],
            [2, 12, 3, 1,  8, 'rejected',   9, 7],
            [2,  3, 3, null, 20, 'pending',  2, 2],
            // gatot subroto
            [3, 10, 4, 1, 12, 'approved',   6, 4],
            [3,  6, 4, null, 18, 'pending',  3, 3],
            [3, 15, 4, null, 10, 'pending',  1, 1],
        ];

        $requestIds = [];
        foreach ($requestLogs as $rl) {
            $createdAt = Carbon::parse('2026-04-01')->addDays($rl[6])->addHours(8 + $rl[7]);
            $updatedAt = $rl[3]
                ? Carbon::parse('2026-04-01')->addDays($rl[6])->addHours(8 + $rl[7] + 2)
                : $createdAt;

            $rid = DB::table('request_log')->insertGetId([
                'branch_id'     => $rl[0],
                'product_id'    => $rl[1],
                'manager_id'    => $rl[2],
                'admin_id'      => $rl[3],
                'requested_qty' => $rl[4],
                'notes'         => null,
                'status'        => $rl[5],
                'created_at'    => $createdAt,
                'updated_at'    => $updatedAt,
            ]);
            $requestIds[] = $rid;
        }

        // stock_log
        $stockLogs = [];

        $saleCount = 0;
        foreach ($orderDataForLog as $od) {
            if ($saleCount >= 80) break;

            $items = DB::table('order_items')
                ->where('order_id', $od['id'])
                ->get();
            if ($items->isEmpty()) continue;

            $firstItem = $items->first();
            $totalQty = $items->sum('qty');
            $stockLogs[] = [
                'branch_id'       => $od['branch_id'],
                'product_id'      => $firstItem->product_id,
                'user_id'         => $od['user_id'],
                'order_id'        => $od['id'],
                'request_id'      => null,
                'activity_type'   => 'sale',
                'quantity_change' => -$totalQty,
                'created_at'      => $od['created_at'],
            ];
            $saleCount++;
        }

        $restockProducts = [1, 3, 15, 16, 17, 23, 25, 29, 30, 31, 32, 5, 7, 12, 18];
        foreach ($restockProducts as $i => $pid) {
            $branchId = ($i % 3) + 1;
            $qty = 10 + ($i * 2);
            $stockLogs[] = [
                'branch_id'       => $branchId,
                'product_id'      => $pid,
                'user_id'         => 1,
                'order_id'        => null,
                'request_id'      => $requestIds[$i % count($requestIds)],
                'activity_type'   => 'restock_approved',
                'quantity_change' => $qty,
                'created_at'      => Carbon::parse('2026-04-01')->addDays(5 + $i * 4)->addHours(10),
            ];
        }

        $adjProducts = [2, 6, 8, 9, 10, 11, 14, 19, 20, 21, 22, 24, 26, 27, 28];
        foreach ($adjProducts as $i => $pid) {
            $branchId = (($i + 1) % 3) + 1;
            $qty = 3 + ($i % 5);
            $stockLogs[] = [
                'branch_id'       => $branchId,
                'product_id'      => $pid,
                'user_id'         => 1,
                'order_id'        => null,
                'request_id'      => null,
                'activity_type'   => 'adjustment',
                'quantity_change' => $qty,
                'created_at'      => Carbon::parse('2026-04-01')->addDays(3 + $i * 4)->addHours(14),
            ];
        }

        $wasteProducts = [4, 13, 20, 21, 26, 27, 28, 30, 31, 32];
        foreach ($wasteProducts as $i => $pid) {
            $branchId = (($i + 2) % 3) + 1;
            $managerId = [2, 3, 4][$i % 3];
            $qty = 1 + ($i % 3);
            $stockLogs[] = [
                'branch_id'       => $branchId,
                'product_id'      => $pid,
                'user_id'         => $managerId,
                'order_id'        => null,
                'request_id'      => null,
                'activity_type'   => 'waste',
                'quantity_change' => -$qty,
                'created_at'      => Carbon::parse('2026-04-01')->addDays(8 + $i * 5)->addHours(16),
            ];
        }

        DB::table('stock_log')->insert($stockLogs);

        $this->rebuildGlobalStocksView();
    }

    // view
    private function rebuildGlobalStocksView(): void
    {
        $branches = DB::table('branches')->where('is_active', true)->get(['name']);
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
            DB::statement("CREATE OR REPLACE VIEW global_stocks_view AS " . implode(" UNION ALL ", $unions));
        }
    }
}
