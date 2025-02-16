<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MemberBarang extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        $barang = Barang::with('kategori')->get(); // Pastikan pembelian memuat data supplier

        return view('member.dashboard.index', compact('barang', 'kategori'));

    }
}
