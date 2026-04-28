<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    protected $primaryKey = 'id_categories';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id_categories');
    }
}