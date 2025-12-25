<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PengajuanController extends Controller
{
    /**
     * Tampilkan daftar Pengajuan (Hanya Baca/Read).
     */
    public function index()
    {
        // Ambil data Pengajuan dengan relasi jemaat dan musibah
        $pengajuans = Pengajuan::with(['jemaat', 'musibah'])
            ->latest()
            ->paginate(20);
            
        return view('pendeta.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Tampilkan detail Pengajuan (Hanya Baca/Read).
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with(['jemaat', 'musibah', 'perhitunganSmart'])
            ->findOrFail($id);
            
        return view('pendeta.pengajuan.show', compact('pengajuan'));
    }
}