<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $primaryKey = 'id_products';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'image_url',
        'base_price',
        'is_available'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_categories');
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'promo_products', 'product_id', 'promo_id');
    }
}