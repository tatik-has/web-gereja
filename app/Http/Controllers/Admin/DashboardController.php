<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use App\Models\Pengajuan;
use App\Models\Keuangan;
use App\Models\Pengumuman;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
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
        // Deteksi nama kolom yang digunakan untuk nilai keuangan
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        $saldoKas = 0;
        
        try {
            // Cek kolom yang tersedia di tabel keuangans
            $keuanganColumns = Schema::getColumnListing('keuangans');
            
            // Tentukan nama kolom yang digunakan (nominal, jumlah, atau amount)
            $amountColumn = 'nominal'; // Default
            if (in_array('jumlah', $keuanganColumns)) {
                $amountColumn = 'jumlah';
            } elseif (in_array('amount', $keuanganColumns)) {
                $amountColumn = 'amount';
            }
            
            // Hitung total pemasukan dan pengeluaran
            $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum($amountColumn);
            $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum($amountColumn);
            $saldoKas = $totalPemasukan - $totalPengeluaran;
        } catch (\Exception $e) {
            // Jika tabel atau kolom tidak ada, set default 0
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
        
        // === STATISTIK BULANAN (Pengajuan per bulan - 6 bulan terakhir) ===
        $pengajuanPerBulan = Pengajuan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        
        // === STATUS SOSIAL JEMAAT ===
        $statusSosialData = Jemaat::select('status_sosial', DB::raw('COUNT(*) as total'))
            ->groupBy('status_sosial')
            ->get();
        
        // === TOTAL PENDETA ===
        try {
            $totalPendeta = Admin::where('role', 'pendeta')->count();
        } catch (\Exception $e) {
            $totalPendeta = 0;
        }
        
        // === PENGUMUMAN TERBARU ===
        try {
            $pengumumanTerbaru = Pengumuman::latest()->take(3)->get();
        } catch (\Exception $e) {
            $pengumumanTerbaru = collect(); // Empty collection jika tabel tidak ada
        }

        return view('admin.dashboard', compact(
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
            'pengajuanPerBulan',
            
            // Keuangan
            'totalPemasukan',
            'totalPengeluaran',
            'saldoKas',
            
            // Lainnya
            'statusSosialData',
            'totalPendeta',
            'pengumumanTerbaru'
        ));
    }
}