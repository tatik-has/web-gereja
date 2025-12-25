<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan pengguna terautentikasi melalui guard 'admin'
        if (!Auth::guard('admin')->check()) {
            // Jika tidak login (atau login sebagai jemaat), alihkan ke login
            return redirect()->route('login'); 
        }

        $user = Auth::guard('admin')->user();

        // Cek apakah role user sesuai dengan role yang diminta di rute
        if ($user->role !== $role) {
            // Jika tidak sesuai, alihkan ke halaman yang sesuai atau error 403
            if ($user->isPendeta()) {
                return redirect()->route('pendeta.dashboard');
            } elseif ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else {
                abort(403, 'Akses Ditolak.');
            }
        }

        return $next($request);
    }
}