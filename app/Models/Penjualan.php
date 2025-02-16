<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'id_member',
        'tanggal_jual',
        'total',
    ];
    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan');
    }
}
