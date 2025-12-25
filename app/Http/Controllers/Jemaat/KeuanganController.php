<?php

namespace App\Http\Controllers\Jemaat;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;

class KeuanganController extends Controller
{
    /**
     * Tampilkan halaman keuangan untuk jemaat (read-only)
     */
    public function index()
    {
        // Ambil data transaksi dengan pagination
        $transaksis = Keuangan::orderBy('tanggal', 'desc')->paginate(20);
        
        // === HITUNG SALDO PER KATEGORI (SAMA SEPERTI ADMIN) ===
        
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
        $total_pengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('nominal');
        
        // 3. SALDO PERSEMBAHAN UMUM (Setelah dikurangi pengeluaran)
        $saldo_persembahan_umum = $persembahan_umum - $total_pengeluaran;
        
        // 4. TOTAL PEMASUKAN KESELURUHAN
        $total_pemasukan = $persembahan_umum + $ucapan_syukur + $persepuluhan;
        
        // 5. SALDO AKHIR KESELURUHAN
        $saldo_akhir = $total_pemasukan - $total_pengeluaran;

        return view('jemaat.keuangan', compact(
            'transaksis',
            'persembahan_umum',
            'ucapan_syukur',
            'persepuluhan',
            'total_pengeluaran',
            'saldo_persembahan_umum',
            'total_pemasukan',
            'saldo_akhir'
        ));
    }
}