<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


// File Model Category - mengelola data pengelompokan menu (Coffee, Non-Coffee, Food)
class Category extends Model
{
    protected $primaryKey = 'id_categories';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    /*
        Relasi Has Many ke tabel Products
        Fungsi: Menarik semua menu yang berada di bawah kategori ini
        Logika bisnis: Untuk membuat filter tabulasi di halaman Self-Order
    */
    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id_categories');
    }
}