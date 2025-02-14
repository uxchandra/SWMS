<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id', 'department_id', 'status', 'tanggal_approve_kadiv', 'tanggal_approve_kagud',
        'approved_by_kadiv', 'approved_by_kagud', 'catatan'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approvedByKadiv()
    {
        return $this->belongsTo(User::class, 'approved_by_kadiv');
    }

    public function approvedByKagud()
    {
        return $this->belongsTo(User::class, 'approved_by_kagud');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->keterangan = static::getKeteranganByStatus($order->status);
        });
        
        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                $order->keterangan = static::getKeteranganByStatus($order->status);
            }
        });
    }

    protected static function getKeteranganByStatus($status)
    {
        return match($status) {
            'Pending' => 'Menunggu approval Kepala Divisi',
            'Approved by Kadiv' => 'Menunggu approval Kepala Gudang',
            'Approved by Kagud' => 'Sedang diproses oleh Staff Gudang',
            'Ready' => 'Barang siap diambil',
            'Completed' => 'Selesai',
            default => ''
        };
    }
}
