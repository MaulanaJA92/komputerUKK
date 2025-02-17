<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::all();
        return view('admin.supplier.index', compact('supplier'));
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
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
        ], [
            'nama_supplier.required' => 'Nama Supplier harus diisi',
            'alamat.required' => 'Alamat Supplier harus diisi',
            'no_telp.required' => 'No telepon Supplier harus diisi',
        ]);

        Supplier::create($credentials);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan!');

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
    public function update(Request $request, Supplier $supplier)
    {
        $credentials = $request->validate([
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
        ], [
            'nama_supplier.required' => 'Nama Supplier harus diisi',
            'alamat.required' => 'Alamat Supplier harus diisi',
            'no_telp.required' => 'No telepon Supplier harus diisi',
        ]);

        $supplier->update($credentials);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $id = $supplier->id;
        $terkait = Pembelian::where('id_supplier', $id)->exists();

        if ($terkait) {
            return redirect()->route('supplier.index')->with('error', 'Supplier tidak bisa dihapus karena memiliki relasi.');
        }
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus!');
    }

}
