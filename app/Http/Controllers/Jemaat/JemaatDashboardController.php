<?php
namespace App\Http\Controllers\Jemaat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengumuman;

class JemaatDashboardController extends Controller
{
    public function index()
    {
        $jemaat = Auth::user(); // Mendapatkan data jemaat yang login
        $pengumumans = Pengumuman::latest()->take(5)->get();
        return view('jemaat.dashboard', compact('jemaat', 'pengumumans'));
    }
}