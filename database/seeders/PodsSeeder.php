<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/*
    Seeder Data Utama
    Tujuan: Memasukkan data awal ke database kosong agar sistem bisa langsung digunakan/diuji
*/
class PodsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insert Roles
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'manager'],
            ['name' => 'customer'],
        ]);

        // 2. Insert Cabang
        // Mengatur jam buka/jam operasional
        DB::table('branches')->insert([
            [
                'name' => 'Dr. Mansyur',
                'address' => 'Jl. Dr. Mansyur No. 80, Padang Bulan Selayang I, Kec. Medan Selayang, Kota Medan',
                'open_time' => null,
                'close_time' => null,
                'is_always_open' => true, // aktif untuk cabang 24 Jam
            ],
            [
                'name' => 'Jamin Ginting',
                'address' => 'Jl. Jamin Ginting, Titi Rantai, Kec. Medan Baru, Kota Medan',
                'open_time' => '07:00:00',
                'close_time' => '22:00:00',
                'is_always_open' => false, // Mengikuti jam operasional
            ],
            [
                'name' => 'Gatot Subroto',
                'address' => 'Jl. Gatot Subroto, Sei Sikambing D, Kec. Medan Petisah, Kota Medan',
                'open_time' => '07:00:00',
                'close_time' => '22:00:00',
                'is_always_open' => false,
            ],
        ]);

        // 3. Insert Users (Akun Internal)
        DB::table('users')->insert([
            // Akun Admin Pusat - Memiliki akses melihat semua data
            [
                'role_id' => 1, 
                'branch_id' => null,
                'name' => 'Admin Pusat',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'), // Wajib di-hash untuk keamanan standar Laravel
                'created_at' => now(),
            ],
            // Akun Manager Cabang - Data operasionalnya hanya untuk cabangnya sendiri
            [
                'role_id' => 2, 
                'branch_id' => 1, // Cabang Dr. Mansyur
                'name' => 'Manager Dr. Mansyur',
                'email' => 'managermansyur@gmail.com',
                'password' => Hash::make('mansyur123'),
                'created_at' => now(),
            ],
            [
                'role_id' => 2,
                'branch_id' => 2, // Cabang Jamin Ginting
                'name' => 'Manager Jamin Ginting',
                'email' => 'managerjamin@gmail.com',
                'password' => Hash::make('ginting123'),
                'created_at' => now(),
            ],
            [
                'role_id' => 2,
                'branch_id' => 3, // Cabang Gatot Subroto
                'name' => 'Manager Gatot Subroto',
                'email' => 'managergatot@gmail.com',
                'password' => Hash::make('subroto123'),
                'created_at' => now(),
            ]
        ]);

        // 4. Insert Kategori Menu
        DB::table('categories')->insert([
            ['name' => 'Coffee'],
            ['name' => 'Non-Coffee'],
            ['name' => 'Food'],
        ]);

        // 5. Insert Produk Awal (Sebagai dummy data untuk diuji di halaman pelanggan)
        DB::table('products')->insert([
            [
                'category_id' => 1,
                'name' => 'Americano',
                'image_url' => null,
                'base_price' => 22000.00,
                'is_available' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Oreo Shake',
                'image_url' => null,
                'base_price' => 26000.00,
                'is_available' => true,
            ]
        ]);
    }
}