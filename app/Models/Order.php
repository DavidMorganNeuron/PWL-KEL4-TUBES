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

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_orders');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id_branches');
    }

    // relasi ke pelanggan yang membuat pesanan ini
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }

    // relasi ke promo
    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id', 'id_promos');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id_orders');
    }
}