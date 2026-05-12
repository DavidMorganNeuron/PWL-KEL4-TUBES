<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/*
    Migrasi Database Pod's
    Tujuan: Membangun seluruh arsitektur database secara berurutan agar
    relasi Foreign Key (FK) tidak mengalami error (tabel utama dibuat lebih dulu)
*/
return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // AUTENTIKASI USER DAN CABANG
        // ==========================================
        
        // Tabel ini menentukan hak akses (Role-Based Access Control)
        // Hanya ada 3 aktor: admin (pusat), manager (cabang), dan customer (pelanggan)
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id_roles');
            $table->string('name')->unique(); 
        });

        // Tabel untuk mendata lokasi fisik cabang Pod's
        Schema::create('branches', function (Blueprint $table) {
            $table->id('id_branches');
            $table->string('name');
            $table->text('address');
            // Jam operasional untuk validasi otomatis (toko buka atau tutup)
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            // Khusus untuk cabang seperti Dr. Mansyur yang buka 24 jam (mengabaikan open/close time)
            $table->boolean('is_always_open')->default(false);
        });

        // Tabel sentral untuk semua pengguna sistem (Admin, Manager, Customer)
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_users');
            $table->foreignId('role_id')->constrained('roles', 'id_roles')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches', 'id_branches')->cascadeOnDelete();
            // branch_id bersifat nullable. Admin Pusat = null, Manager = wajib terikat 1 cabang
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // ==========================================
        // KATALOG PRODUK
        // ==========================================
        
        // Mengelompokkan menu (misal: Coffee, Non-Coffee, Food)
        Schema::create('categories', function (Blueprint $table) {
            $table->id('id_categories');
            $table->string('name');
        });

        // Katalog menu utama yang akan ditampilkan di aplikasi
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_products');
            $table->foreignId('category_id')->constrained('categories', 'id_categories')->cascadeOnDelete();
            $table->string('name');
            // Menyimpan path gambar yang diunggah Admin. Nullable jika gambar belum tersedia
            $table->string('image_url')->nullable();
            $table->decimal('base_price', 15, 2);
            // Fitur Tutup Menu Global. Jika false, menu ini otomatis hilang dari semua cabang
            $table->boolean('is_available')->default(true);
        });

        // ==========================================
        // PROMOSI
        // ==========================================
        
        // Mesin diskon otomatis
        Schema::create('promos', function (Blueprint $table) {
            $table->id('id_promos');
            // Jika branch_id terisi, promo hanya berlaku di cabang tersebut. Jika null = promo global
            $table->foreignId('branch_id')->nullable()->constrained('branches', 'id_branches')->cascadeOnDelete();
            $table->string('name');
            $table->enum('discount_type', ['percentage', 'nominal']);
            $table->decimal('discount_value', 15, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
        });

        // Tabel pivot (Many-to-Many) untuk menentukan produk spesifik apa saja yang dapat diskon
        Schema::create('promo_products', function (Blueprint $table) {
            $table->foreignId('promo_id')->constrained('promos', 'id_promos')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products', 'id_products')->cascadeOnDelete();
        });

        // ==========================================
        // DATA STOK PRODUK DAN REQUEST
        // ==========================================
        
        // Tabel untuk mencatat pengajuan restock dari Manager Cabang ke Admin Pusat
        Schema::create('request_log', function (Blueprint $table) {
            $table->id('id_request_log');
            $table->foreignId('branch_id')->constrained('branches', 'id_branches');
            $table->foreignId('product_id')->constrained('products', 'id_products');
            $table->foreignId('manager_id')->constrained('users', 'id_users'); // Siapa yang minta
            $table->foreignId('admin_id')->nullable()->constrained('users', 'id_users'); // Siapa yang ACC
            $table->integer('requested_qty');
            // Alur bisnis: pending -> disetujui (stok otomatis nambah) atau ditolak
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // 3 Tabel Stok Fisik Cabang (Pemisahan data per lokasi)
        // physical_qty: Stok real di dapur
        // reserved_qty: Stok yang sedang di-hold saat customer di halaman checkout (Soft-Lock Mechanism)
        Schema::create('stock_branch_dr_mansyur', function (Blueprint $table) {
            $table->id('id_stock_branch_dr_mansyur');
            $table->foreignId('product_id')->constrained('products', 'id_products')->cascadeOnDelete();
            $table->integer('physical_qty')->default(0);
            $table->integer('reserved_qty')->default(0);
        });

        Schema::create('stock_branch_gatot_subroto', function (Blueprint $table) {
            $table->id('id_stock_branch_gatot_subroto');
            $table->foreignId('product_id')->constrained('products', 'id_products')->cascadeOnDelete();
            $table->integer('physical_qty')->default(0);
            $table->integer('reserved_qty')->default(0);
        });

        Schema::create('stock_branch_jamin_ginting', function (Blueprint $table) {
            $table->id('id_stock_branch_jamin_ginting');
            $table->foreignId('product_id')->constrained('products', 'id_products')->cascadeOnDelete();
            $table->integer('physical_qty')->default(0);
            $table->integer('reserved_qty')->default(0);
        });

        // ==========================================
        // SALES DAN TRANSAKSI
        // ==========================================
        
        // Buku Induk Pendapatan / Transaksi
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_orders');
            $table->foreignId('branch_id')->constrained('branches', 'id_branches');
            // Semua pembeli WAJIB punya akun.
            $table->foreignId('user_id')->constrained('users', 'id_users'); 
            $table->string('order_number')->unique();
            $table->foreignId('promo_id')->nullable()->constrained('promos', 'id_promos');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_discount', 15, 2);
            $table->decimal('grand_total', 15, 2); // Nilai uang final yang masuk ke kas
            // Siklus pesanan: unpaid -> paid -> cooking -> completed or canceled.
            $table->enum('status', ['pending_payment', 'paid', 'cooking', 'completed', 'canceled'])->default('pending_payment');
            $table->string('cancel_reason')->nullable(); // Wajib diisi manager jika terjadi pembatalan darurat
            $table->timestamps();
        });

        // Rincian produk dari setiap pesanan (Cart)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_order_items');
            $table->foreignId('order_id')->constrained('orders', 'id_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products', 'id_products');
            $table->integer('qty');
            // Menyimpan harga saat itu agar laporan lama tidak berubah jika harga dasar naik
            $table->decimal('base_price', 15, 2);
            $table->decimal('discount_amount', 15, 2);
            $table->decimal('subtotal_price', 15, 2);
        });

        // Proses pembayaran
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payments');
            $table->foreignId('order_id')->constrained('orders', 'id_orders')->cascadeOnDelete();
            $table->enum('method', ['QRIS', 'E_Wallet']);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->dateTime('paid_at')->nullable();
        });

        // Tabel pergerakan barang
        Schema::create('stock_log', function (Blueprint $table) {
            $table->id('id_stock_log');
            $table->foreignId('branch_id')->constrained('branches', 'id_branches');
            $table->foreignId('product_id')->constrained('products', 'id_products');
            $table->foreignId('user_id')->constrained('users', 'id_users'); // Siapa pelakunya
            $table->foreignId('order_id')->nullable()->constrained('orders', 'id_orders'); // Terisi jika karena penjualan
            $table->foreignId('request_id')->nullable()->constrained('request_log', 'id_request_log'); // Terisi jika dari ACC admin
            // sale = laku, restock = ditambah admin, waste = bahan rusak/dibuang, adjustment = koreksi manual
            $table->enum('activity_type', ['sale', 'restock_approved', 'adjustment', 'waste']);
            $table->integer('quantity_change'); // Bisa plus atau minus
            $table->timestamp('created_at')->useCurrent();
        });

        // Membuat (Laporan Global)
        // Ini memungkinkan Admin Pusat melihat total stok seluruh cabang
        DB::statement("
            CREATE OR REPLACE VIEW global_stocks_view AS
            SELECT 'Dr. Mansyur' AS branch_name, product_id, physical_qty, reserved_qty FROM stock_branch_dr_mansyur
            UNION ALL
            SELECT 'Gatot Subroto', product_id, physical_qty, reserved_qty FROM stock_branch_gatot_subroto
            UNION ALL
            SELECT 'Jamin Ginting', product_id, physical_qty, reserved_qty FROM stock_branch_jamin_ginting
        ");
    }

    public function down(): void
    {
        // Fungsi ini dijalankan saat melakukan migrate:fresh
        // Urutan drop harus dibalik (dari bawah ke atas) agar tidak melanggar batasan Foreign Key.
        DB::statement("DROP VIEW IF EXISTS global_stocks_view");
        Schema::dropIfExists('stock_log');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('stock_branch_jamin_ginting');
        Schema::dropIfExists('stock_branch_gatot_subroto');
        Schema::dropIfExists('stock_branch_dr_mansyur');
        Schema::dropIfExists('request_log');
        Schema::dropIfExists('promo_products');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('roles');
    }
};