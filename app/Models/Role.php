<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    File Model Role
    Tujuan: Menampilkan tabel 'roles' di database yang menyimpan tingkatan hak akses (Admin, Manager, Customer)
*/
class Role extends Model
{
    // Mengubah default pencarian ID Laravel menjadi custom ID kita
    protected $primaryKey = 'id_roles';
    
    // Tabel 'roles' kita di migration tidak memiliki timestamps (created_at/updated_at)
    // Jadi kita matikan fitur otomatis Laravel ini agar tidak error saat menyimpan data
    public $timestamps = false;

    // Kolom yang aman untuk diisi massal
    protected $fillable = [
        'name'
    ];

    /*
        Relasi Has Many ke tabel Users
        Fungsi: Mengambil semua pengguna yang memiliki role tertentu
    */
    public function users() {
        return $this->hasMany(User::class, 'role_id', 'id_roles');
    }
}