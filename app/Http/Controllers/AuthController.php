<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Login;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'pemimpin') {
                return redirect()->route('dashboard.admin')->with('success', 'Login berhasil');
            } else if (Auth::user()->role == 'kasir') {
                return redirect()->route('dashboard.kasir')->with('success', 'Login berhasil');
            } else if (Auth::user()->role == 'member') {
                return redirect()->route('dashboard.member')->with('success', 'Login berhasil');
            } 

        }

    
        
        // Jika login gagal, kirim error ke tampilan
        session()->flash('error', 'Email atau password salah');
        return back()->withInput();
        
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil');
    }
  
}
