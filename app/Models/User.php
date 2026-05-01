<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/*
    Model User
    Merepresentasikan tabel 'users' di database. Model ini mengatur akun
    untuk Admin, Manager, maupun Customer.
*/
class User extends Authenticatable
{
    use Notifiable;

    // Karena kita menggunakan penamaan id custom (id_users)
    // alih-alih bawaan Laravel ('id'), kita wajib memberi tahu Eloquent di sini agar tidak error.
    protected $primaryKey = 'id_users';

    // Daftar kolom yang diizinkan untuk diisi secara massal.
    // Mencegah user nakal menyisipkan data ke kolom yang tidak seharusnya.
    protected $fillable = [
        'role_id', 'branch_id', 'name', 'email', 'password',
    ];

    // Fungsi ini otomatis memotong nama lengkap menjadi maksimal 2 kata sebagai display name
    // Cara memanggilnya di file blade: {{ Auth::user()->display_name }}
    public function getDisplayNameAttribute()
    {
        // Pecah nama lengkap menjadi array berdasarkan spasi
        $words = explode(' ', $this->name);
        
        // Ambil maksimal 2 kata pertama, lalu gabungkan kembali dengan spasi
        return implode(' ', array_slice($words, 0, 2));
    }

    /*
        Relasi ke tabel Roles.
        Fungsi ini memudahkan kita mengecek posisi user, misal: $user->role->name
    */
    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id_roles');
    }

    /*
        Relasi ke tabel Branches.
        Mengambil data cabang tempat staf/manager ditugaskan.
     */
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id', 'id_branches');
    }
}