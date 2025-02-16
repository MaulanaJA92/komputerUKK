<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportPdf()
    {
        $pembelian = Pembelian::with(['supplier', 'details.barang'])->get(); // Tambahkan relasi barang

        $pdf = Pdf::loadView('admin.exportpembelian.index', compact('pembelian'));
        return $pdf->stream('laporan_penjualan.pdf');  // Menampilkan PDF tanpa download
    }
}
