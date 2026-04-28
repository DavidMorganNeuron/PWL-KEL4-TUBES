<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrderItem extends Model
{
    protected $primaryKey = 'id_order_items';
    
    
    public $timestamps = false;

    protected $fillable = [
        'order_id', 
        'product_id', 
        'qty', 
        'base_price', 
        'discount_amount', 
        'subtotal_price'
    ];


    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id_products');
    }
}