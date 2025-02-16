<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $members = Member::all();
        $barangs = Barang::all();
        $penjualan = Penjualan::with(['member', 'details'])->get(); // Load relasi pembelianDetail

        return view('kasir.penjualan.index', compact('penjualan', 'members', 'barangs'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validasi input
        $request->validate([
            'id_member' => 'required|exists:members,id',
            'tanggal_jual' => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:1',
        ]);

        try {
            DB::beginTransaction(); // ✅ Mulai transaksi
        
            // Simpan pembelian
            $penjualan = Penjualan::create([
                'id_member' => $request->id_member,
                'tanggal_jual' => $request->tanggal_jual ,
                'total' => 0 // Total dihitung dari subtotal barang
            ]);
        
            $totalHarga = 0;
        
            foreach ($request->barang_id as $key => $barangId) {
                $barang = Barang::find($barangId);
        
                // ✅ Cek stok sebelum dikurangi
                if ($barang->stok < $request->jumlah[$key]) {
                    DB::rollBack(); // ❌ Batalkan transaksi jika stok kurang
                    return redirect()->route('penjualan.index')->with('error', "Stok barang {$barang->nama} tidak mencukupi! Tersisa: {$barang->stok}");
                }
        
                $subtotal = $barang->harga * $request->jumlah[$key];
                $totalHarga += $subtotal;
        
                // ✅ Kurangi stok hanya jika stok cukup
                $barang->stok -= $request->jumlah[$key];
                $barang->save();
        
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id,
                    'id_barang' => $barangId,
                    'jumlah_barang' => $request->jumlah[$key],
                    'sub_total' => $subtotal
                ]);
            }
        
            // ✅ Update total harga penjualan
            $penjualan->update(['total' => $totalHarga]);
        
            DB::commit(); // ✅ Simpan transaksi ke database
        
            return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Batalkan semua perubahan jika terjadi error
            return redirect()->route('penjualan.index')->with('error', 'Terjadi kesalahan saat menambahkan penjualan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Penjualan $penjualan)
    {
        // Validasi input
        $request->validate([
            'id_member' => 'required|exists:members,id',
            'tanggal_jual' => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:1',
        ]);

        try {
            DB::beginTransaction(); // ✅ Mulai transaksi
        
            // Cari data penjualan berdasarkan ID

        
            // ✅ Kembalikan stok barang sebelum menghapus detail lama
            $detailLama = PenjualanDetail::where('id_penjualan', $penjualan->id)->get();
            foreach ($detailLama as $detail) {
                $barang = Barang::find($detail->id_barang);
                if ($barang) {
                    $barang->stok += $detail->jumlah_barang; // Kembalikan stok
                    $barang->save();
                }
            }
        
            // ✅ Hapus detail penjualan lama
            PenjualanDetail::where('id_penjualan', $penjualan->id)->delete();
        
            // ✅ Update data penjualan
            $penjualan->update([
                'id_member' => $request->id_member,
                'tanggal_jual' => $request->tanggal_jual,
                'total' => 0 // Total akan dihitung ulang
            ]);
        
            $totalHarga = 0;
        
            // ✅ Simpan ulang detail penjualan dengan pengecekan stok
            foreach ($request->barang_id as $key => $barangId) {
                $barang = Barang::find($barangId);
        
                // ✅ Cek apakah stok cukup
                if ($barang->stok < $request->jumlah[$key]) {
                    DB::rollBack();
                    return redirect()->route('penjualan.index')->with('error', "Stok barang {$barang->nama} tidak mencukupi! Tersisa: {$barang->stok}");
                }
        
                $subtotal = $barang->harga * $request->jumlah[$key];
                $totalHarga += $subtotal;
        
                // ✅ Kurangi stok setelah dicek
                $barang->stok -= $request->jumlah[$key];
                $barang->save();
        
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id,
                    'id_barang' => $barangId,
                    'jumlah_barang' => $request->jumlah[$key],
                    'sub_total' => $subtotal
                ]);
            }
        
            // ✅ Update total harga penjualan
            $penjualan->update(['total' => $totalHarga]);
        
            DB::commit(); // ✅ Simpan transaksi ke database
        
            return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Batalkan semua perubahan jika terjadi error
            return redirect()->route('penjualan.index')->with('error', 'Terjadi kesalahan saat memperbarui penjualan.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        try {
            DB::beginTransaction(); // ✅ Mulai transaksi
        
            // ✅ Ambil semua detail penjualan sebelum dihapus
            $details = $penjualan->details;
        
            // ✅ Kembalikan stok barang sebelum menghapus detail penjualan
            foreach ($details as $detail) {
                $barang = Barang::find($detail->id_barang);
                if ($barang) {
                    $barang->stok += $detail->jumlah_barang; // Kembalikan stok barang
                    $barang->save();
                }
            }
        
            // ✅ Hapus detail penjualan setelah stok dikembalikan
            $penjualan->details()->delete();
        
            // ✅ Hapus data penjualan utama
            $penjualan->delete();
        
            DB::commit(); // ✅ Simpan perubahan jika semua berhasil
        
            return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dihapus dan stok dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Batalkan semua perubahan jika terjadi error
            return redirect()->route('penjualan.index')->with('error', 'Terjadi kesalahan saat menghapus penjualan.');
        }
    }
}
