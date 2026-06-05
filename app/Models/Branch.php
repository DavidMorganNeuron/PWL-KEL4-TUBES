<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_branches';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'open_time',
        'close_time',
        'is_always_open',
        'is_active',
        'is_closing',
        'deleted_at',
    ];

    protected $casts = [
        'is_always_open' => 'boolean',
        'is_active' => 'boolean',
        'is_closing' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'branch_id', 'id_branches');
    }

    public function managers()
    {
        return $this->hasMany(User::class, 'branch_id', 'id_branches');
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'branch_id', 'id_branches')->where('role_id', 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('deleted_at');
    }

    public function isOpen(): bool
    {
        if ($this->is_always_open) {
            return true;
        }

        if (!$this->open_time || !$this->close_time) {
            return false;
        }

        $now = now()->format('H:i:s');
        return $now >= $this->open_time && $now <= $this->close_time;
    }

    public function stockTableName(): string
    {
        $normalized = strtolower(preg_replace('/[\s.]+/', '_', trim($this->name)));
        $normalized = preg_replace('/[^a-z0-9_]/', '', $normalized);
        return 'stock_branch_' . $normalized;
    }

    public function hasActiveOrders(): bool
    {
        return $this->orders()
            ->whereIn('status', ['paid', 'cooking'])
            ->exists();
    }
}
