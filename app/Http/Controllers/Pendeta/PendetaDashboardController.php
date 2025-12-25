<?php
namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use App\Models\Pengajuan; // PASTIKAN ANDA SUDAH MENGIMPOR MODEL INI

class PendetaDashboardController extends Controller
{
    public function index()
    {
        // === PASTIKAN VARIABEL INI DIHITUNG DAN DIKIRIM KE VIEW ===
        $totalJemaat = Jemaat::count();
        $pengajuanBaru = Pengajuan::where('status', 'pending')->count();
        
        // Memastikan nama variabel dan compact() sudah benar
        return view('pendeta.dashboard', compact('totalJemaat', 'pengajuanBaru'));
    }
}