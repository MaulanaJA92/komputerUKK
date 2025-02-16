<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::all();
        return view('kasir.member.index', compact('members'));
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
            'nama_member' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|email|unique:logins,email',
            'password' => 'required|string|min:6',
        ], [
            'nama_member.required' => 'Nama member harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
        ]);

        // Simpan ke database
        Member::create([
            'nama_member' => $request->nama_member,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => $request->password, // Hash password sebelum disimpan
        ]);
        Login::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'member',
        ]);

        return redirect()->route('member.index')->with('success', 'Member berhasil ditambahkan!');
    
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
    public function update(Request $request, Member $member)
    {
       
        $credentials = $request->validate([
            'nama_member' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'email' => [
                'required',
                'email',
                Rule::unique('logins', 'email')->ignore($member->email, 'email')
            ],
            'password' => 'nullable|string|min:6', // Password opsional (hanya diubah jika diisi)
        ], [
            'nama_member.required' => 'Nama member harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
        ]);
    
        Login::where('email', $member->email)->update([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // Update data member
        $member->update([
            'nama_member' => $request->nama_member,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => $request->password ?$request->password : $member->password, // Update password hanya jika diisi dan hash password
        ]);
     

    
        return redirect()->route('member.index')->with('success', 'Member berhasil diperbarui!');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        Login::where('email', $member->email)->delete();
        $member->delete();

        return redirect()->route('member.index')->with('success', 'Member berhasil dihapus!');
    }
}
