<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role1 = null, $role2 = null)
    {
        // Jika tidak ada role yang diberikan, abort 403
        if ($role1 === null) {
            return abort(403, 'Role tidak diberikan dalam middleware.');
        }

        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Buat array role yang diperbolehkan
        $allowedRoles = [$role1];
        if ($role2 !== null) {
            $allowedRoles[] = $role2;
        }

        // Log untuk debugging
        \Log::info('User mencoba akses:', [
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'allowed_roles' => $allowedRoles
        ]);

        // Cek apakah user memiliki salah satu role yang diperbolehkan
        if (!in_array(Auth::user()->role, $allowedRoles, true)) {
            return abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
