<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use App\Models\Pengajuan;
use App\Models\Keuangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PendetaDashboardController extends Controller
{
    public function index()
    {
        // === STATISTIK JEMAAT ===
        $totalJemaat = Jemaat::count();
        $jemaatAktif = Jemaat::where('approved', true)->count();
        $jemaatPending = Jemaat::where('approved', false)->count();
        
        // === STATISTIK PENGAJUAN ===
        $pengajuanBaru = Pengajuan::where('status', 'pending')->count();
        $pengajuanDiterima = Pengajuan::where('status', 'diterima')->count();
        $pengajuanDitolak = Pengajuan::where('status', 'ditolak')->count();
        $totalPengajuan = Pengajuan::count();
        
        // === STATISTIK KEUANGAN ===
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        $saldoKas = 0;
        
        try {
            $keuanganColumns = Schema::getColumnListing('keuangans');
            
            $amountColumn = 'nominal';
            if (in_array('jumlah', $keuanganColumns)) {
                $amountColumn = 'jumlah';
            } elseif (in_array('amount', $keuanganColumns)) {
                $amountColumn = 'amount';
            }
            
            $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum($amountColumn);
            $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum($amountColumn);
            $saldoKas = $totalPemasukan - $totalPengeluaran;
        } catch (\Exception $e) {
            $totalPemasukan = 0;
            $totalPengeluaran = 0;
            $saldoKas = 0;
        }
        
        // === PENGAJUAN TERBARU (5 terakhir) ===
        $pengajuanTerbaru = Pengajuan::with('jemaat')
            ->latest()
            ->take(5)
            ->get();
        
        // === JEMAAT TERBARU (5 terakhir) ===
        $jemaatTerbaru = Jemaat::latest()
            ->take(5)
            ->get();
        
        // === STATUS SOSIAL JEMAAT ===
        $statusSosialData = Jemaat::select('status_sosial', DB::raw('COUNT(*) as total'))
            ->groupBy('status_sosial')
            ->get();

        return view('pendeta.dashboard', compact(
            // Jemaat
            'totalJemaat',
            'jemaatAktif',
            'jemaatPending',
            'jemaatTerbaru',
            
            // Pengajuan
            'pengajuanBaru',
            'pengajuanDiterima',
            'pengajuanDitolak',
            'totalPengajuan',
            'pengajuanTerbaru',
            
            // Keuangan
            'totalPemasukan',
            'totalPengeluaran',
            'saldoKas',
            
            // Lainnya
            'statusSosialData'
        ));
    }
}