<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        // ================================================================
        // PRODUK AWAL
        // ================================================================
        // PodsSeeder sudah insert: Americano (id 1), Oreo Shake (id 2)
        DB::table('products')->insert([
            // -- Coffee (category_id: 1) --
            ['category_id' => 1, 'name' => 'Caramel Macchiato', 'image_url' => null, 'base_price' => 28000, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Brown Sugar Latte',  'image_url' => null, 'base_price' => 29000, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Cold Brew',          'image_url' => null, 'base_price' => 26000, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Cappuccino',         'image_url' => null, 'base_price' => 25000, 'is_available' => true],
            // -- Non-Coffee (category_id: 2) --
            ['category_id' => 2, 'name' => 'Matcha Latte',       'image_url' => null, 'base_price' => 29000, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Taro Latte',         'image_url' => null, 'base_price' => 28000, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Chocolate Frappe',   'image_url' => null, 'base_price' => 30000, 'is_available' => true],
            // -- Food (category_id: 3) --
            ['category_id' => 3, 'name' => 'Croissant Plain',    'image_url' => null, 'base_price' => 22000, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Croissant Almond',   'image_url' => null, 'base_price' => 24000, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Banana Cake',        'image_url' => null, 'base_price' => 20000, 'is_available' => true],
        ]);

        // Customer Seed
        DB::table('users')->insert([
            ['role_id' => 3, 'branch_id' => null, 'name' => 'Andi Wijaya',    'email' => 'andi@gmail.com',    'password' => Hash::make('customer123'), 'created_at' => now()],
            ['role_id' => 3, 'branch_id' => null, 'name' => 'Sari Dewi',      'email' => 'sari@gmail.com',    'password' => Hash::make('customer123'), 'created_at' => now()],
            ['role_id' => 3, 'branch_id' => null, 'name' => 'Benny Kusuma',   'email' => 'benny@gmail.com',   'password' => Hash::make('customer123'), 'created_at' => now()],
            ['role_id' => 3, 'branch_id' => null, 'name' => 'Diana Putri',    'email' => 'diana@gmail.com',   'password' => Hash::make('customer123'), 'created_at' => now()],
            ['role_id' => 3, 'branch_id' => null, 'name' => 'Rizky Hamdani',  'email' => 'rizky@gmail.com',   'password' => Hash::make('customer123'), 'created_at' => now()],
            ['role_id' => 3, 'branch_id' => null, 'name' => 'Lina Hartati',   'email' => 'lina@gmail.com',    'password' => Hash::make('customer123'), 'created_at' => now()],
            ['role_id' => 3, 'branch_id' => null, 'name' => 'Fajar Nugroho',  'email' => 'fajar@gmail.com',   'password' => Hash::make('customer123'), 'created_at' => now()],
        ]);

        // ================================================================
        // STOK FISIK KETIGA CABANG
        // ================================================================
        $allProductIds = range(1, 12);

        $stockDrMansyur = [
            // [product_id, physical_qty, reserved_qty]
            [1,  5,  0],  
            [2,  30, 2],
            [3,  8,  1],  
            [4,  20, 3],
            [5,  15, 0],  
            [6,  25, 0],
            [7,  3,  0],  
            [8,  18, 2],
            [9,  22, 0],
            [10, 40, 5],
            [11, 28, 3],
            [12, 35, 0],
        ];

        $stockJaminGinting = [
            [1,  22, 0], [2,  18, 1], [3,  30, 2],
            [4,  25, 0], [5,  20, 0], [6,  28, 1],
            [7,  15, 0], [8,  22, 0], [9,  19, 0],
            [10, 50, 4], [11, 35, 2], [12, 40, 0],
        ];

        $stockGatotSubroto = [
            [1,  18, 0], [2,  7,  0],
            [3,  12, 1], [4,  6,  0],
            [5,  25, 0], [6,  20, 0],
            [7,  9,  0],
            [8,  15, 1], [9,  11, 0],
            [10, 32, 2], [11, 20, 1], [12, 28, 0],
        ];

        foreach ($stockDrMansyur as [$productId, $physical, $reserved]) {
            DB::table('stock_branch_dr_mansyur')->insert([
                'product_id'   => $productId,
                'physical_qty' => $physical,
                'reserved_qty' => $reserved,
            ]);
        }

        foreach ($stockJaminGinting as [$productId, $physical, $reserved]) {
            DB::table('stock_branch_jamin_ginting')->insert([
                'product_id'   => $productId,
                'physical_qty' => $physical,
                'reserved_qty' => $reserved,
            ]);
        }

        foreach ($stockGatotSubroto as [$productId, $physical, $reserved]) {
            DB::table('stock_branch_gatot_subroto')->insert([
                'product_id'   => $productId,
                'physical_qty' => $physical,
                'reserved_qty' => $reserved,
            ]);
        }

        // ================================================================
        // PROMO TESTING
        // ================================================================
        DB::table('promos')->insert([
            [
                // promo nasional: berlaku di semua cabang
                'branch_id'      => null,
                'name'           => 'Happy Hour',
                'discount_type'  => 'percentage',
                'discount_value' => 15,
                'start_date'     => Carbon::now()->startOfDay(),
                'end_date'       => Carbon::now()->addDays(30),
                'is_active'      => true,
            ],
            [
                // promo lokal: hanya cabang Dr. Mansyur
                'branch_id'      => 1,
                'name'           => 'Weekend Deal',
                'discount_type'  => 'nominal',
                'discount_value' => 5000,
                'start_date'     => Carbon::now()->startOfDay(),
                'end_date'       => Carbon::now()->addDays(7),
                'is_active'      => true,
            ],
        ]);

        DB::table('promo_products')->insert([
            ['promo_id' => 1, 'product_id' => 1],
            ['promo_id' => 1, 'product_id' => 3],
            ['promo_id' => 1, 'product_id' => 7],
        ]);

        DB::table('promo_products')->insert([
            ['promo_id' => 2, 'product_id' => 10],
            ['promo_id' => 2, 'product_id' => 11],
            ['promo_id' => 2, 'product_id' => 12],
        ]);

        // ================================================================
        // ORDERS, ORDER_ITEMS, DAN PAYMENTS
        // ================================================================
        // Membuat 10 order historis (selesai) + 5 order hari ini (berbagai status aktif)
        // untuk mensimulasikan kondisi KDS dan laporan yang realistis.

        $today     = Carbon::now();
        $yesterday = Carbon::now()->subDay();

        $ordersData = [
            // --- ORDER HISTORIS (kemarin, status completed) ---
            // order 1: Andi di Dr. Mansyur
            [
                'branch_id' => 1, 'user_id' => 5, 'order_number' => 'PODS-' . Carbon::yesterday()->format('Ymd') . '-AA0001',
                'promo_id' => null, 'subtotal' => 56000, 'total_discount' => 0, 'grand_total' => 56000,
                'status' => 'completed', 'cancel_reason' => null,
                'created_at' => $yesterday->copy()->setTime(10, 15),
                'items' => [
                    ['product_id' => 1, 'qty' => 2, 'base_price' => 22000, 'discount_amount' => 0, 'subtotal_price' => 44000],
                    ['product_id' => 10,'qty' => 1, 'base_price' => 22000, 'discount_amount' => 0, 'subtotal_price' => 22000],
                ],
            ],
            // order 2: Sari di Dr. Mansyur
            [
                'branch_id' => 1, 'user_id' => 6, 'order_number' => 'PODS-' . Carbon::yesterday()->format('Ymd') . '-AA0002',
                'promo_id' => 1, 'subtotal' => 57000, 'total_discount' => 4200, 'grand_total' => 52800,
                'status' => 'completed', 'cancel_reason' => null,
                'created_at' => $yesterday->copy()->setTime(11, 30),
                'items' => [
                    ['product_id' => 3, 'qty' => 1, 'base_price' => 28000, 'discount_amount' => 4200, 'subtotal_price' => 23800],
                    ['product_id' => 2, 'qty' => 1, 'base_price' => 26000, 'discount_amount' => 0,    'subtotal_price' => 26000],
                    ['product_id' => 7, 'qty' => 1, 'base_price' => 29000, 'discount_amount' => 0,    'subtotal_price' => 29000],
                ],
            ],
            // order 3: Benny di Dr. Mansyur, canceled
            [
                'branch_id' => 1, 'user_id' => 7, 'order_number' => 'PODS-' . Carbon::yesterday()->format('Ymd') . '-AA0003',
                'promo_id' => null, 'subtotal' => 55000, 'total_discount' => 0, 'grand_total' => 55000,
                'status' => 'canceled', 'cancel_reason' => 'Bahan matcha habis mendadak.',
                'created_at' => $yesterday->copy()->setTime(13, 0),
                'items' => [
                    ['product_id' => 7, 'qty' => 1, 'base_price' => 29000, 'discount_amount' => 0, 'subtotal_price' => 29000],
                    ['product_id' => 4, 'qty' => 1, 'base_price' => 29000, 'discount_amount' => 0, 'subtotal_price' => 29000],
                ],
            ],
            // order 4: Diana di Dr. Mansyur
            [
                'branch_id' => 1, 'user_id' => 8, 'order_number' => 'PODS-' . Carbon::yesterday()->format('Ymd') . '-AA0004',
                'promo_id' => null, 'subtotal' => 52000, 'total_discount' => 0, 'grand_total' => 52000,
                'status' => 'completed', 'cancel_reason' => null,
                'created_at' => $yesterday->copy()->setTime(14, 20),
                'items' => [
                    ['product_id' => 1, 'qty' => 1, 'base_price' => 22000, 'discount_amount' => 0, 'subtotal_price' => 22000],
                    ['product_id' => 9, 'qty' => 1, 'base_price' => 30000, 'discount_amount' => 0, 'subtotal_price' => 30000],
                ],
            ],
            // order 5: Rizky di Dr. Mansyur
            [
                'branch_id' => 1, 'user_id' => 9, 'order_number' => 'PODS-' . Carbon::yesterday()->format('Ymd') . '-AA0005',
                'promo_id' => 2, 'subtotal' => 73000, 'total_discount' => 5000, 'grand_total' => 68000,
                'status' => 'completed', 'cancel_reason' => null,
                'created_at' => $yesterday->copy()->setTime(15, 45),
                'items' => [
                    ['product_id' => 3,  'qty' => 2, 'base_price' => 28000, 'discount_amount' => 0,    'subtotal_price' => 56000],
                    ['product_id' => 11, 'qty' => 1, 'base_price' => 24000, 'discount_amount' => 5000, 'subtotal_price' => 19000],
                ],
            ],

            // --- ORDER HARI INI: berbagai status untuk testing KDS ---

            // order 6: Diana — PAID (menunggu diproses)
            [
                'branch_id' => 1, 'user_id' => 8, 'order_number' => 'PODS-' . $today->format('Ymd') . '-BB0001',
                'promo_id' => null, 'subtotal' => 78000, 'total_discount' => 0, 'grand_total' => 78000,
                'status' => 'paid', 'cancel_reason' => null,
                'created_at' => $today->copy()->setTime(14, 5),
                'items' => [
                    ['product_id' => 4, 'qty' => 2, 'base_price' => 29000, 'discount_amount' => 0, 'subtotal_price' => 58000],
                    ['product_id' => 11,'qty' => 1, 'base_price' => 24000, 'discount_amount' => 0, 'subtotal_price' => 24000],
                ],
            ],
            // order 7: Benny — PAID (menunggu diproses)
            [
                'branch_id' => 1, 'user_id' => 7, 'order_number' => 'PODS-' . $today->format('Ymd') . '-BB0002',
                'promo_id' => null, 'subtotal' => 130000, 'total_discount' => 0, 'grand_total' => 130000,
                'status' => 'paid', 'cancel_reason' => null,
                'created_at' => $today->copy()->setTime(14, 10),
                'items' => [
                    ['product_id' => 4, 'qty' => 2, 'base_price' => 29000, 'discount_amount' => 0, 'subtotal_price' => 58000],
                    ['product_id' => 1, 'qty' => 1, 'base_price' => 22000, 'discount_amount' => 0, 'subtotal_price' => 22000],
                    ['product_id' => 7, 'qty' => 1, 'base_price' => 29000, 'discount_amount' => 0, 'subtotal_price' => 29000],
                    ['product_id' => 12,'qty' => 1, 'base_price' => 20000, 'discount_amount' => 0, 'subtotal_price' => 20000],
                ],
            ],
            // order 8: Sari — COOKING (sedang dimasak)
            [
                'branch_id' => 1, 'user_id' => 6, 'order_number' => 'PODS-' . $today->format('Ymd') . '-BB0003',
                'promo_id' => null, 'subtotal' => 52000, 'total_discount' => 0, 'grand_total' => 52000,
                'status' => 'cooking', 'cancel_reason' => null,
                'created_at' => $today->copy()->setTime(14, 18),
                'items' => [
                    ['product_id' => 1, 'qty' => 1, 'base_price' => 22000, 'discount_amount' => 0, 'subtotal_price' => 22000],
                    ['product_id' => 7, 'qty' => 1, 'base_price' => 29000, 'discount_amount' => 0, 'subtotal_price' => 29000],
                ],
            ],
            // order 9: Andi — COOKING (sedang dimasak)
            [
                'branch_id' => 1, 'user_id' => 5, 'order_number' => 'PODS-' . $today->format('Ymd') . '-BB0004',
                'promo_id' => null, 'subtotal' => 95000, 'total_discount' => 0, 'grand_total' => 95000,
                'status' => 'cooking', 'cancel_reason' => null,
                'created_at' => $today->copy()->setTime(14, 22),
                'items' => [
                    ['product_id' => 3, 'qty' => 1, 'base_price' => 28000, 'discount_amount' => 0, 'subtotal_price' => 28000],
                    ['product_id' => 4, 'qty' => 2, 'base_price' => 29000, 'discount_amount' => 0, 'subtotal_price' => 58000],
                ],
            ],
            // order 10: Lina — COMPLETED (selesai hari ini)
            [
                'branch_id' => 1, 'user_id' => 10, 'order_number' => 'PODS-' . $today->format('Ymd') . '-BB0005',
                'promo_id' => null, 'subtotal' => 63000, 'total_discount' => 0, 'grand_total' => 63000,
                'status' => 'completed', 'cancel_reason' => null,
                'created_at' => $today->copy()->setTime(13, 31),
                'items' => [
                    ['product_id' => 7, 'qty' => 1, 'base_price' => 29000, 'discount_amount' => 0, 'subtotal_price' => 29000],
                    ['product_id' => 10,'qty' => 1, 'base_price' => 22000, 'discount_amount' => 0, 'subtotal_price' => 22000],
                ],
            ],
        ];

        foreach ($ordersData as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);

            $orderId = DB::table('orders')->insertGetId(array_merge($orderData, [
                'updated_at' => $orderData['created_at'],
            ]));

            foreach ($items as $item) {
                DB::table('order_items')->insert(array_merge($item, [
                    'order_id' => $orderId,
                ]));
            }

            // payments: semua order kecuali yang canceled punya record payment success
            if ($orderData['status'] !== 'canceled') {
                DB::table('payments')->insert([
                    'order_id' => $orderId,
                    'method'   => 'QRIS',
                    'status'   => 'success',
                    'paid_at'  => $orderData['created_at'],
                ]);
            }
        }

        // ================================================================
        // REQUEST_LOG
        // ================================================================
        DB::table('request_log')->insert([
            [
                // pengajuan lama: sudah disetujui admin
                'branch_id'     => 1,
                'product_id'    => 2,
                'manager_id'    => 2,
                'admin_id'      => 1,
                'requested_qty' => 20,
                'status'        => 'approved',
                'created_at'    => Carbon::now()->subDays(4),
                'updated_at'    => Carbon::now()->subDays(4)->addHours(2),
            ],
            [
                // pengajuan yang ditolak admin
                'branch_id'     => 1,
                'product_id'    => 8,
                'manager_id'    => 2,
                'admin_id'      => 1,
                'requested_qty' => 10,
                'status'        => 'rejected',
                'created_at'    => Carbon::now()->subDays(2),
                'updated_at'    => Carbon::now()->subDays(2)->addHours(5),
            ],
            [
                // pengajuan baru: masih pending
                'branch_id'     => 1,
                'product_id'    => 7,
                'manager_id'    => 2,
                'admin_id'      => null,
                'requested_qty' => 30,
                'status'        => 'pending',
                'created_at'    => Carbon::now()->subHours(3),
                'updated_at'    => Carbon::now()->subHours(3),
            ],
            [
                // pengajuan kedua pending
                'branch_id'     => 1,
                'product_id'    => 1,
                'manager_id'    => 2,
                'admin_id'      => null,
                'requested_qty' => 25,
                'status'        => 'pending',
                'created_at'    => Carbon::now()->subHours(1),
                'updated_at'    => Carbon::now()->subHours(1),
            ],
        ]);

        // ================================================================
        // STOCK_LOG
        // ================================================================
        // Mencerminkan penjualan dari order historis dan restock yang disetujui
        DB::table('stock_log')->insert([
            ['branch_id' => 1, 'product_id' => 1,  'user_id' => 5,  'order_id' => 1, 'request_id' => null, 'activity_type' => 'sale',              'quantity_change' => -2,  'created_at' => $yesterday->copy()->setTime(10, 15)],
            ['branch_id' => 1, 'product_id' => 10, 'user_id' => 5,  'order_id' => 1, 'request_id' => null, 'activity_type' => 'sale',              'quantity_change' => -1,  'created_at' => $yesterday->copy()->setTime(10, 15)],
            ['branch_id' => 1, 'product_id' => 3,  'user_id' => 6,  'order_id' => 2, 'request_id' => null, 'activity_type' => 'sale',              'quantity_change' => -1,  'created_at' => $yesterday->copy()->setTime(11, 30)],
            ['branch_id' => 1, 'product_id' => 2,  'user_id' => 6,  'order_id' => 2, 'request_id' => null, 'activity_type' => 'sale',              'quantity_change' => -1,  'created_at' => $yesterday->copy()->setTime(11, 30)],
            ['branch_id' => 1, 'product_id' => 7,  'user_id' => 6,  'order_id' => 2, 'request_id' => null, 'activity_type' => 'sale',              'quantity_change' => -1,  'created_at' => $yesterday->copy()->setTime(11, 30)],
            ['branch_id' => 1, 'product_id' => 7,  'user_id' => 2,  'order_id' => 3, 'request_id' => null, 'activity_type' => 'waste',             'quantity_change' => 0,   'created_at' => $yesterday->copy()->setTime(13, 5)],
            ['branch_id' => 1, 'product_id' => 4,  'user_id' => 2,  'order_id' => 3, 'request_id' => null, 'activity_type' => 'waste',             'quantity_change' => 0,   'created_at' => $yesterday->copy()->setTime(13, 5)],
            ['branch_id' => 1, 'product_id' => 2,  'user_id' => 1,  'order_id' => null, 'request_id' => 1, 'activity_type' => 'restock_approved',  'quantity_change' => +20, 'created_at' => Carbon::now()->subDays(4)->addHours(2)],
            ['branch_id' => 1, 'product_id' => 1,  'user_id' => 8,  'order_id' => 4, 'request_id' => null, 'activity_type' => 'sale',              'quantity_change' => -1,  'created_at' => $yesterday->copy()->setTime(14, 20)],
            ['branch_id' => 1, 'product_id' => 9,  'user_id' => 8,  'order_id' => 4, 'request_id' => null, 'activity_type' => 'sale',              'quantity_change' => -1,  'created_at' => $yesterday->copy()->setTime(14, 20)],
            ['branch_id' => 1, 'product_id' => 7,  'user_id' => 10, 'order_id' => 10, 'request_id' => null, 'activity_type' => 'sale',             'quantity_change' => -1,  'created_at' => $today->copy()->setTime(13, 31)],
            ['branch_id' => 1, 'product_id' => 10, 'user_id' => 10, 'order_id' => 10, 'request_id' => null, 'activity_type' => 'sale',             'quantity_change' => -1,  'created_at' => $today->copy()->setTime(13, 31)],
        ]);
    }
}