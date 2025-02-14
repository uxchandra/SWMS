<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluarItem extends Model
{
    protected $fillable = ['barang_keluar_id', 'barang_id', 'quantity'];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
