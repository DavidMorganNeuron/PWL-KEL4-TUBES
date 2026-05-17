<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    Model RequestLog
    Merepresentasikan tabel 'request_log' — mencatat setiap pengajuan restock
    dari manager cabang ke admin pusat beserta hasil keputusannya.
*/
class RequestLog extends Model
{
    protected $table = 'request_log';
 
    protected $primaryKey = 'id_request_log';
 
    protected $fillable = [
        'branch_id',
        'product_id',
        'manager_id',
        'admin_id',
        'requested_qty',
        'status',
        'notes',
    ];
 
    /* relasi ke cabang pengaju */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id_branches');
    }
 
    /* relasi ke produk yang diminta */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_products');
    }
 
    /* relasi ke user manager yang mengajukan */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id_users');
    }
 
    /* relasi ke user admin yang memproses */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id_users');
    }
 
    /* hanya pengajuan milik cabang tertentu */
    public function scopeForBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
 
    /* hanya pengajuan dengan status pending */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}