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
        // Memanggil PodsSeeder untuk dieksekusi oleh Laravel
        $this->call([
            PodsSeeder::class,
        ]);
    }
}