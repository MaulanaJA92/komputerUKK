<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::withCount('barang')->get();
        return view('admin.kategori.index', compact('kategori'));
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
        $credentials = $request->validate([
            'nama_kategori' => 'required',
        ], [
            'nama_kategori.required' => 'Nama kategori harus diisi'
        ]);
    
        Kategori::create($credentials);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    
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
    public function update(Request $request, Kategori $kategori)
    {
        $credentials = $request->validate([
            'nama_kategori' => 'required',
        ], [
            'nama_kategori.required' => 'Nama kategori harus diisi'
        ]);
    
        $kategori->update($credentials);
    
     return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diedit!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
{
    $kategori->delete();
    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
}

}
