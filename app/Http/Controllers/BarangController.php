<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use Illuminate\Http\Request;

class BarangController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        $barang = Barang::with('kategori')->get(); // Pastikan pembelian memuat data supplier

        return view('admin.barang.index', compact('barang', 'kategori'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
        $credentials = $request->validate([
            'id_kategori' => 'required',
            'nama_barang' => 'required',
            'warna' => 'required',
            'detail_barang' => 'required',
            'berat' => 'required|numeric', 
            'harga' => 'required|numeric', 
            'harga_jual' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           
        ], [
            'id_kategori.required' => 'Kategori harus diisi',
            'nama_barang.required' => 'Nama barang harus diisi',
            'detail_barang.required' => 'Detail barang harus diisi',
            'berat.required' => 'Berat harus diisi',
            'berat.numeric' => 'Berat harus berupa angka',
            'harga.required' => 'Harga harus diisi',
            'harga_jual.required' => 'Harga jual harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
            // 'foto.required' => 'Foto harus diunggah',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus JPG atau PNG',
            'foto.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            
        ]);
         //jika file foto ada yang terupload
         if(!empty($request->foto)){
            //maka proses berikut yang dijalankan
            $fileName = 'foto-'.uniqid().'.'.$request->foto->extension();
            //setelah tau fotonya sudah masuk maka tempatkan ke public
            $request->foto->move(public_path('image'), $fileName);
        } else {
            $fileName = 'nophoto.jpg';
        }
        $credentials['foto'] = $fileName;
        
    
        Barang::create($credentials);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    
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
    public function update(Request $request, Barang $barang)
    {
        $credentials = $request->validate([
            'id_kategori' => 'required',
            'nama_barang' => 'required',
            'warna' => 'required',
            'detail_barang' => 'required',
            'berat' => 'required|numeric', 
            'harga' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'id_kategori.required' => 'Kategori harus diisi',
            'nama_barang.required' => 'Nama barang harus diisi',
            'detai_barang.required' => 'Detail barang harus diisi',
            'berat.required' => 'Berat harus diisi',
            'berat.numeric' => 'Berat harus berupa angka',
            'harga.required' => 'Harga harus diisi',
            'harga_jual.required' => 'Harga jual harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
            // 'foto.required' => 'Foto harus diunggah',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus JPG atau PNG',
            'foto.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
        ]);
        
        $fotoLama = $barang->foto;
       
        if ($request->hasFile('foto')) {
            // Jika foto lama ada dan bukan 'nophoto.png', hapus file lama
            if (!empty($fotoLama) && $fotoLama !== 'nophoto.png' && file_exists(public_path('image/' . $fotoLama))) {
                unlink(public_path('image/' . $fotoLama));
            }
    
            // Simpan foto baru
            $fileName = 'foto-' . $barang->id . '.' . $request->foto->extension();
            $request->foto->move(public_path('image'), $fileName);
        } else {
            // Jika tidak ada foto baru, gunakan foto lama
            $fileName = $fotoLama;
        }
        $credentials['foto'] = $fileName;
        $barang->update($credentials);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate!');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        $id=$barang->id;
        $terkait = PembelianDetail::where('id_barang', $id)->exists();

if ($terkait) {
 return redirect()->route('barang.index')->with('error', 'Barang tidak bisa dihapus karena memiliki relasi.');
}

$barang->delete();
return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    
    }
}
