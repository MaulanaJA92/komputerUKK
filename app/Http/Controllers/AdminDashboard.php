<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Member;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

class AdminDashboard extends Controller
{
    public function index()
    {
        
        $jumlahBarang = Barang::count(); // Jumlah barang yang tersedia
        $jumlahMember = Member::count(); // Jumlah member
        
        // Menghitung total barang terjual
        $totalBarangTerjual =PenjualanDetail::sum('jumlah_barang'); 
        
        // Menghitung keuntungan: (jumlah * harga_jual) - (jumlah * harga_beli)
        $keuntungan = PenjualanDetail::join('barangs', 'penjualan_details.id_barang', '=', 'barangs.id')
            ->selectRaw('SUM(penjualan_details.jumlah_barang * barangs.harga_jual) - SUM(penjualan_details.jumlah_barang * barangs.harga) as total_keuntungan')
            ->value('total_keuntungan');
        
        return view('admin.dashboard.index', compact('jumlahBarang', 'totalBarangTerjual', 'jumlahMember', 'keuntungan'));
        
    }
}
