<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jemaat; // Import Jemaat

class LoginController extends Controller
{
    // Menampilkan halaman login
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

        // 1. Coba login sebagai ADMIN
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // 2. Coba login sebagai JEMAAT
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

    // Proses Logout
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}