<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


// File Model Payment - Melacak status pembayaran dengan metode seperti QRIS / E-Wallet
class Payment extends Model
{
    protected $primaryKey = 'id_payments';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 
        'method', 
        'status', 
        'paid_at'
    ];

    // Relasi Belongs To ke pesanan terkait model ini akan memanggil relasi order() untuk mengubah status pesanan menjadi 'paid'
    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }
}