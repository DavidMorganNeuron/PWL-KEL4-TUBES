<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


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