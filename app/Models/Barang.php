<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'id_kategori',
        'nama_barang',
        'harga',
        'harga_jual',
        'detail_barang',
        'berat',
        'foto',
    ];
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
    public function detailPembelian()
    {
        return $this->hasMany(PembelianDetail::class, 'id_barang');
    }
}
