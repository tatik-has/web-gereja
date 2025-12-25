<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jemaat; 
use App\Models\Admin; // <<< TAMBAHKAN INI

class LoginController extends Controller
{
    // ... (Fungsi showLoginForm tidak berubah)
    public function showLoginForm()
    {
        return view('auth.login'); // Satu view untuk semua
    }

    // Memproses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // 1. Coba login sebagai ADMIN/PENDETA (menggunakan guard 'admin')
        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                // Jika role adalah 'admin', arahkan ke dashboard Admin
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isPendeta()) {
                // Jika role adalah 'pendeta', arahkan ke dashboard Pendeta
                return redirect()->intended(route('pendeta.dashboard'));
            }
        }

        // 2. Coba login sebagai JEMAAT (menggunakan guard 'web')
        if (Auth::guard('web')->attempt($credentials)) {
            
            // Cek apakah jemaat sudah di-approve
            $jemaat = Auth::guard('web')->user();
            
            if (!$jemaat->approved) {
                // Jika belum approve, paksa logout dan beri pesan error
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum diaktifkan oleh Admin.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();
            return redirect()->intended(route('jemaat.dashboard'));
        }

        // 3. Jika semua gagal
        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->withInput($request->only('email'));
    }

    // Proses Logout (diperbaiki agar Pendeta juga bisa logout)
    public function logout(Request $request)
    {
        // Cek apakah pengguna saat ini adalah Admin/Pendeta
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } 
        // Cek apakah pengguna saat ini adalah Jemaat
        elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}