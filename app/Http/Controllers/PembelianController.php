<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PembelianController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $suppliers = Supplier::all();
        $barangs = Barang::all();
        $pembelian = Pembelian::with(['supplier', 'details'])->get(); // Load relasi pembelianDetail

        return view('admin.pembelian.index', compact('pembelian', 'suppliers', 'barangs'));


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
            'id_supplier' => 'required|exists:suppliers,id',
            'tanggal_beli' => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:1',
        ]);

        try {
            DB::beginTransaction(); // ✅ Mulai transaksi

            // Simpan pembelian
            $pembelian = Pembelian::create([
                'id_supplier' => $request->id_supplier,
                'tanggal_beli' => $request->tanggal_beli,
                'total' => 0 // Total dihitung dari subtotal barang
            ]);

            $totalHarga = 0;

            // Simpan detail pembelian
            foreach ($request->barang_id as $key => $barangId) {
                $barang = Barang::find($barangId);

                // ✅ Cek apakah barang ditemukan
                if (!$barang) {
                    DB::rollBack();
                    return redirect()->route('pembelian.index')->with('error', "Barang dengan ID {$barangId} tidak ditemukan!");
                }

                $subtotal = $barang->harga * $request->jumlah[$key];
                $totalHarga += $subtotal;

                // ✅ Tambahkan stok setelah semua validasi lolos
                $barang->stok += $request->jumlah[$key];
                $barang->save();

                PembelianDetail::create([
                    'id_pembelian' => $pembelian->id,
                    'id_barang' => $barangId,
                    'jumlah_barang' => $request->jumlah[$key],
                    'sub_total' => $subtotal
                ]);
            }

            // ✅ Update total harga pembelian
            $pembelian->update(['total' => $totalHarga]);

            DB::commit(); // ✅ Simpan transaksi ke database

            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Batalkan semua perubahan jika terjadi error
            return redirect()->route('pembelian.index')->with('error', 'Terjadi kesalahan saat menambahkan pembelian.');
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
    public function update(Request $request, Pembelian $pembelian)
    {
        // Validasi input
        $request->validate([
            'id_supplier' => 'required|exists:suppliers,id',
            'tanggal_beli' => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:1',
        ]);

        try {
            DB::beginTransaction(); // ✅ Mulai transaksi



            // ✅ Kembalikan stok barang sebelum menghapus detail pembelian lama
            $oldDetails = PembelianDetail::where('id_pembelian', $pembelian->id)->get();
            foreach ($oldDetails as $detail) {
                $barang = Barang::find($detail->id_barang);
                if ($barang) {
                    $barang->stok -= $detail->jumlah_barang; // Kembalikan stok ke keadaan sebelumnya
                    $barang->save();
                }
            }

            // Hapus detail pembelian lama
            PembelianDetail::where('id_pembelian', $pembelian->id)->delete();

            // ✅ Update data pembelian
            $pembelian->update([
                'id_supplier' => $request->id_supplier,
                'tanggal_beli' => $request->tanggal_beli,
                'total' => 0 // Akan dihitung ulang
            ]);

            $totalHarga = 0;

            // Simpan ulang detail pembelian dengan stok yang benar
            foreach ($request->barang_id as $key => $barangId) {
                $barang = Barang::find($barangId);

                // ✅ Cek apakah barang ditemukan
                if (!$barang) {
                    DB::rollBack();
                    return redirect()->route('pembelian.index')->with('error', "Barang dengan ID {$barangId} tidak ditemukan!");
                }

                $subtotal = $barang->harga * $request->jumlah[$key];
                $totalHarga += $subtotal;
                $barang->stok += $request->jumlah[$key]; // Tambah stok barang baru
                $barang->save();

                PembelianDetail::create([
                    'id_pembelian' => $pembelian->id,
                    'id_barang' => $barangId,
                    'jumlah_barang' => $request->jumlah[$key],
                    'sub_total' => $subtotal
                ]);
            }

            // Update total harga pembelian
            $pembelian->update(['total' => $totalHarga]);

            DB::commit(); // ✅ Simpan transaksi ke database

            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Batalkan semua perubahan jika terjadi error
            return redirect()->route('pembelian.index')->with('error', 'Terjadi kesalahan saat memperbarui pembelian.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        try {
            DB::beginTransaction(); // ✅ Mulai transaksi

            // ✅ Ambil semua detail pembelian sebelum dihapus
            $details = $pembelian->details;

            // ✅ Kembalikan stok barang sebelum menghapus detail pembelian
            foreach ($details as $detail) {
                $barang = Barang::find($detail->id_barang);
                if ($barang) {
                    $barang->stok -= $detail->jumlah_barang; // Kurangi stok barang
                    $barang->save();
                }
            }

            // ✅ Hapus detail pembelian setelah stok dikembalikan
            $pembelian->details()->delete();

            // ✅ Hapus data pembelian utama
            $pembelian->delete();

            DB::commit(); // ✅ Simpan perubahan jika semua berhasil

            return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus dan stok dikurangi!');
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Batalkan semua perubahan jika terjadi error
            return redirect()->route('pembelian.index')->with('error', 'Terjadi kesalahan saat menghapus pembelian.');
        }
    }
  

}
