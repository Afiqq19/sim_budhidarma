<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login, DAN apakah jabatannya (role) sesuai dengan yang diminta di routes
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request); // Silakan masuk!
        }

        // Jika tidak sesuai (misal TU mau masuk ke Admin), langsung tendang dengan pesan Error 403
        abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk masuk ke ruangan ini!');
    }
}