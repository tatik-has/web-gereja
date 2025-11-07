<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin(){ return view('auth.admin_login'); }

    public function login(Request $req){
        $creds = $req->only('email','password');
        if (Auth::guard('web')->attempt($creds)){
            // we use default guard for admin (or configure guard 'admin' if prefer)
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['email'=>'Email atau password salah']);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
