<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/*
    File Induk Seeder
    Tujuan: Menjadi titik kumpul/pintu masuk ketika kita
    menjalankan perintah `php artisan db:seed`.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PodsSeeder::class,    // data awal: roles, branches, manager, categories, 2 produk
            ManagerSeeder::class, // data testing: produk lengkap, stok, orders, request_log, stock_log
        ]);
    }
}