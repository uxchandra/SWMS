<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = [
        'tanggal_masuk',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(BarangMasukItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
