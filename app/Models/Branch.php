<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


// Model Branch - Menampilkan data lokasi dan jam operasional cabang
class Branch extends Model
{
    // Timpa default ID Laravel
    protected $primaryKey = 'id_branches';

    /*
        Memberi tahu Laravel bahwa tabel 'branches' di database kita tidak memiliki 
        kolom 'created_at' dan 'updated_at', sehingga Eloquent tidak akan mencoba mengisinya.
    */
    public $timestamps = false; 

    protected $fillable = [
        'name', 'address', 'open_time', 'close_time', 'is_always_open'
    ];

    // Relasi ke tabel Orders. Berguna untuk menarik total pendapatan per cabang, misal: $branch->orders()->sum('grand_total')
    public function orders() {
        return $this->hasMany(Order::class, 'branch_id', 'id_branches');
    }
}