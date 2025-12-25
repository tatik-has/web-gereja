<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;

class KeuanganController extends Controller
{
    /**
     * Tampilkan laporan Keuangan (Read-Only) untuk Pendeta
     */
    public function index()
    {
        // Ambil data transaksi dengan pagination
        $keuangans = Keuangan::orderBy('tanggal', 'desc')->paginate(20);
        
        // === HITUNG SALDO PER KATEGORI (SAMA SEPERTI ADMIN & JEMAAT) ===
        
        // 1. PEMASUKAN PER KATEGORI
        $persembahan_umum = Keuangan::where('jenis', 'pemasukan')
                                     ->where('kategori', 'persembahan_umum')
                                     ->sum('nominal');
        
        $ucapan_syukur = Keuangan::where('jenis', 'pemasukan')
                                  ->where('kategori', 'ucapan_syukur')
                                  ->sum('nominal');
        
        $persepuluhan = Keuangan::where('jenis', 'pemasukan')
                                 ->where('kategori', 'persepuluhan')
                                 ->sum('nominal');
        
        // 2. TOTAL PENGELUARAN
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('nominal');
        
        // 3. SALDO PERSEMBAHAN UMUM
        $saldo_persembahan_umum = $persembahan_umum - $totalPengeluaran;
        
        // 4. TOTAL PEMASUKAN
        $totalPemasukan = $persembahan_umum + $ucapan_syukur + $persepuluhan;
        
        // 5. SALDO AKHIR
        $saldo_akhir = $totalPemasukan - $totalPengeluaran;

        return view('pendeta.keuangan.index', compact(
            'keuangans',
            'persembahan_umum',
            'ucapan_syukur',
            'persepuluhan',
            'totalPengeluaran',
            'saldo_persembahan_umum',
            'totalPemasukan',
            'saldo_akhir'
        ));
    }
}