<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


// Model Branch - Menampilkan data lokasi dan jam operasional cabang
class Branch extends Model
{
    protected $primaryKey = 'id_branches';
    public $timestamps = false;
 
    protected $fillable = [
        'name',
        'address',
        'open_time',
        'close_time',
        'is_always_open',
    ];
 
    /* relasi ke semua pesanan yang masuk ke cabang ini */
    public function orders()
    {
        return $this->hasMany(Order::class, 'branch_id', 'id_branches');
    }
 
    /* relasi ke manager yang bertugas di cabang ini */
    public function managers()
    {
        return $this->hasMany(User::class, 'branch_id', 'id_branches');
    }
 
    /*
        menentukan apakah cabang sedang buka berdasarkan waktu server.
        cabang is_always_open = true selalu mengembalikan true.
    */
    public function isOpen(): bool
    {
        if ($this->is_always_open) {
            return true;
        }
 
        if (!$this->open_time || !$this->close_time) {
            return false;
        }
 
        $now   = now()->format('H:i:s');
        return $now >= $this->open_time && $now <= $this->close_time;
    }
}