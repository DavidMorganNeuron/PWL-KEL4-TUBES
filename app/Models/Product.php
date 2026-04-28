<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


// Model Product - menampilkan katalog menu yang tersedia di sistem Pod's
class Product extends Model
{
    protected $primaryKey = 'id_products';
    public $timestamps = false;

    // Data-data pokok menu, termasuk status ketersediaannya
    protected $fillable = [
        'category_id',
        'name',
        'image_url',
        'base_price',
        'is_available'
    ];

    /*
        Relasi Belongs To ke tabel Categories
        Fungsi: Mengetahui kategori dari produk ini (Misal: Americano -> Coffee)
    */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_categories');
    }

    /*
        Relasi Belongs To Many ke tabel Promos (melalui pivot table promo_products)
        Logika bisnis: Satu produk bisa saja diikutkan dalam beberapa event promo berbeda 
        di waktu yang berbeda
    */
    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'promo_products', 'product_id', 'promo_id');
    }
}