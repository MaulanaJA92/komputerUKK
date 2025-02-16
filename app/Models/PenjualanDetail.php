<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $fillable = ['id_penjualan', 'id_barang',  'jumlah_barang', 'sub_total'];
}
