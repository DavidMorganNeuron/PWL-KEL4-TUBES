<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    Model StockLog
    Merepresentasikan tabel 'stock_log' — audit trail pergerakan stok fisik.
    Setiap baris adalah satu kejadian: penjualan, penambahan restock, waste, atau koreksi manual.
*/
class StockLog extends Model
{
    protected $table = 'stock_log';
 
    protected $primaryKey = 'id_stock_log';
 
    /* stock_log hanya memiliki created_at, tidak ada updated_at */
    public $timestamps = false;
 
    protected $fillable = [
        'branch_id',
        'product_id',
        'user_id',
        'order_id',
        'request_id',
        'activity_type',
        'quantity_change',
        'created_at',
    ];
 
    /* relasi ke cabang tempat kejadian terjadi */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id_branches');
    }
 
    /* relasi ke produk yang bergerak */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_products');
    }
 
    /* relasi ke user pelaku */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }
 
    /* relasi ke order (hanya terisi untuk activity_type 'sale') */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }
 
    /* relasi ke request_log (hanya terisi untuk activity_type 'restock_approved') */
    public function requestLog()
    {
        return $this->belongsTo(RequestLog::class, 'request_id', 'id_request_log');
    }
 
    /* filter berdasarkan cabang */
    public function scopeForBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
 
    /* filter berdasarkan jenis aktivitas */
    public function scopeOfType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }
}