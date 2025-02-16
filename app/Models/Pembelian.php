<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $fillable = [
        'id_supplier',
        'tanggal_beli',
        'total',
    ];

 
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function details()
    {
        return $this->hasMany(PembelianDetail::class, 'id_pembelian');
    }
   
}
