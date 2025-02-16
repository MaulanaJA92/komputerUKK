<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Jika user sudah login, redirect ke dashboard
         
        $role = Auth::user()->role; // Assign the role from the authenticated user
        return redirect("/dashboard/$role");
        }

        return $next($request);
    }
}
