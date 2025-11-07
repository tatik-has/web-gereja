<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use App\Models\Pengajuan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJemaat = Jemaat::count();
        $pengajuanBaru = Pengajuan::where('status', 'pending')->count();
        return view('admin.dashboard', compact('totalJemaat', 'pengajuanBaru'));
    }
}