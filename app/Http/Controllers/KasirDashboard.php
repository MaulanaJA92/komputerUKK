<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Member;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

class KasirDashboard extends Controller
{
    public function index()
    {
        
        $jumlahBarang = Barang::count(); // Jumlah barang yang tersedia
        $jumlahMember = Member::count(); // Jumlah member
        
        // Menghitung total barang terjual
        $totalBarangTerjual =PenjualanDetail::sum('jumlah_barang'); 
        
      
        
        return view('admin.dashboard.index', compact('jumlahBarang', 'totalBarangTerjual', 'jumlahMember'));
        
    }
}
