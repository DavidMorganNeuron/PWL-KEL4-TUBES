<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // AUTENTIKASI USER DAN CABANG
        // ==========================================
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id_roles');
            $table->string('name')->unique(); // 'admin', 'manager', 'customer'
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id('id_branches');
            $table->string('name');
            $table->text('address');
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_always_open')->default(false); // Dr. Mansyur always open
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id('id_users');
            $table->foreignId('role_id')->constrained('roles', 'id_roles')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches', 'id_branches')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // ==========================================
        // KATALOG PRODUK
        // ==========================================
        Schema::create('categories', function (Blueprint $table) {
            $table->id('id_categories');
            $table->string('name');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id('id_products');
            $table->foreignId('category_id')->constrained('categories', 'id_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('image_url')->nullable();
            $table->decimal('base_price', 15, 2);
            $table->boolean('is_available')->default(true);
        });

        // ==========================================
        // PROMOSI
        // ==========================================
        Schema::create('promos', function (Blueprint $table) {
            $table->id('id_promos');
            $table->foreignId('branch_id')->nullable()->constrained('branches', 'id_branches')->cascadeOnDelete();
            $table->string('name');
            $table->enum('discount_type', ['percentage', 'nominal']);
            $table->decimal('discount_value', 15, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
        });

        Schema::create('promo_products', function (Blueprint $table) {
            $table->foreignId('promo_id')->constrained('promos', 'id_promos')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products', 'id_products')->cascadeOnDelete();
        });

        // ==========================================
        // DATA STOK PRODUK DAN REQUEST
        // ==========================================
        Schema::create('request_log', function (Blueprint $table) {
            $table->id('id_request_log');
            $table->foreignId('branch_id')->constrained('branches', 'id_branches');
            $table->foreignId('product_id')->constrained('products', 'id_products');
            $table->foreignId('manager_id')->constrained('users', 'id_users');
            $table->foreignId('admin_id')->nullable()->constrained('users', 'id_users');
            $table->integer('requested_qty');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // 3 Tabel Stok Fisik Cabang
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_orders');
            $table->foreignId('branch_id')->constrained('branches', 'id_branches');
            $table->foreignId('user_id')->constrained('users', 'id_users'); // Wajib login untuk transaksi
            $table->string('order_number')->unique();
            $table->foreignId('promo_id')->nullable()->constrained('promos', 'id_promos');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_discount', 15, 2);
            $table->decimal('grand_total', 15, 2);
            $table->enum('status', ['pending_payment', 'paid', 'cooking', 'completed', 'canceled'])->default('pending_payment');
            $table->string('cancel_reason')->nullable(); // Wajib diisi jika dibatalkan
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_order_items');
            $table->foreignId('order_id')->constrained('orders', 'id_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products', 'id_products');
            $table->integer('qty');
            $table->decimal('base_price', 15, 2);
            $table->decimal('discount_amount', 15, 2);
            $table->decimal('subtotal_price', 15, 2);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payments');
            $table->foreignId('order_id')->constrained('orders', 'id_orders')->cascadeOnDelete();
            $table->enum('method', ['QRIS', 'E_Wallet']); // Metode gerbang pembayaran
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->dateTime('paid_at')->nullable();
        });

        Schema::create('stock_log', function (Blueprint $table) {
            $table->id('id_stock_log');
            $table->foreignId('branch_id')->constrained('branches', 'id_branches');
            $table->foreignId('product_id')->constrained('products', 'id_products');
            $table->foreignId('user_id')->constrained('users', 'id_users');
            $table->foreignId('order_id')->nullable()->constrained('orders', 'id_orders');
            $table->foreignId('request_id')->nullable()->constrained('request_log', 'id_request_log');
            $table->enum('activity_type', ['sale', 'restock_approved', 'adjustment', 'waste']);
            $table->integer('quantity_change');
            $table->timestamp('created_at')->useCurrent();
        });

        // Membuat Laporan Global
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