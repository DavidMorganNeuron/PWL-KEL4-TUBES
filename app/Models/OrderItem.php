<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    File Model OrderItem
    Tujuan: Menyimpan rincian produk per pesanan (cart).
    Model ini adalah jembatan yang menghubungkan transaksi dengan produk.
*/
class OrderItem extends Model
{
    // Deklarasi mutlak untuk Custom Primary Key
    protected $primaryKey = 'id_order_items';
    
    // Mematikan timestamps karena rincian pesanan bersifat statis 
    // pada saat transaksi terjadi, dan waktu transaksi sudah dicatat di tabel induk 'orders'
    public $timestamps = false;

    // Daftar kolom rincian pesanan dan harga
    protected $fillable = [
        'order_id', 
        'product_id', 
        'qty', 
        'base_price', 
        'discount_amount', 
        'subtotal_price'
    ];

    /*
        Relasi Belongs To ke induk pesanan (Order)
        Fungsi: Mengetahui rincian menu ini milik transaksi yang mana
    */
    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }

    /*
        Relasi Belongs To ke master produk (Product)
        Fungsi: Memanggil detail produk aslinya (seperti nama menu atau gambar)
        untuk ditampilkan di halaman riwayat pelanggan atau Kitchen Display
    */
    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id_products');
    }
}