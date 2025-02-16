<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Member;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Riwayat extends Controller
{
     public function index()
    {
        $user = Auth::user();

    // Cari member berdasarkan email
    $member = Member::where('email', $user->email)->first();

    // Ambil semua barang
    $barangs = Barang::all();

    // Ambil semua data penjualan dengan relasi member dan details
    $penjualan = Penjualan::with(['member', 'details'])->get();

    return view('member.riwayat.index', compact('penjualan', 'member', 'barangs'));
    }
}
