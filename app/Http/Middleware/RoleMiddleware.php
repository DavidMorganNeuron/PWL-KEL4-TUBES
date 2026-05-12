<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/*
    Middleware Role (Penjaga Pintu Keamanan)
    Tujuan: Memastikan pengguna yang masuk ke suatu halaman memiliki hak akses (role) yang sesuai.
    Jika Admin mencoba masuk ke halaman Customer, sistem akan memblokirnya.
*/
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Logika 1: Cek apakah pengguna sudah login atau belum
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Logika 2: Ambil nama role dari pengguna yang sedang login
        $userRole = Auth::user()->role->name;

        // Logika 3: Cocokkan role pengguna dengan role yang diminta oleh Route
        if ($userRole !== $role) {
            abort(403, 'Akses Ditolak. Ruang kerja ini bukan untuk Anda.');
        }

        // Jika aman, persilakan masuk ke proses selanjutnya (halaman yang dituju)
        return $next($request);
    }
}