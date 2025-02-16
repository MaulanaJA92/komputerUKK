<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{

    protected $fillable = ['id_pembelian', 'id_barang', 'jumlah_barang', 'sub_total'];
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
