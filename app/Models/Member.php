<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'nama_member',
        'alamat',
        'no_telp',
        'email',
        'password',
    ];
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_member');
    }
}
