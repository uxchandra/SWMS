<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $fillable = [
        'order_id',
        'tanggal_keluar',
        'user_id',
    ];

    // Relasi ke user yang membuat transaksi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke items barang keluar
    public function items()
    {
        return $this->hasMany(BarangKeluarItem::class);
    }

    // Relasi ke order jika barang keluar terkait dengan order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
