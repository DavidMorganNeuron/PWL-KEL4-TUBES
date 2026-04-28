<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


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

    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }
}