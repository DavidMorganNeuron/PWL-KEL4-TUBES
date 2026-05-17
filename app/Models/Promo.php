<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Promo - program diskon nasional maupun lokal per cabang
class Promo extends Model
{
    protected $primaryKey = 'id_promos';
    public $timestamps = false;

    protected $fillable = [
        'branch_id',
        'name',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
    ];

    // relasi ke produk yang mendapatkan promo ini
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promo_products', 'promo_id', 'product_id');
    }
}