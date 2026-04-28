<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Order - Inti transaksi sistem Pod's. Menyimpan status pesanan dan pergerakan uang real
class Order extends Model
{
    // Timpa default ID Laravel
    protected $primaryKey = 'id_orders';

    // Kolom-kolom yang menyimpan riwayat finansial dan pergerakan status pesanan
    protected $fillable = [
        'branch_id', 'user_id', 'order_number', 'promo_id', 
        'subtotal', 'total_discount', 'grand_total', 
        'status', 'cancel_reason'
    ];

    /*
        Relasi (Has Many) ke rincian item belanja (OrderItems).
        Memungkinkan kita memanggil isi keranjang dari cart, 
        misal: menampilkan rincian Americano dan Oreo Shake pada struk pembayaran
    */
    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_orders');
    }
}