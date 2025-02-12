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
}
