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
        
        // Hitung total pemasukan, pengeluaran, dan saldo
        $total_pemasukan = Keuangan::where('jenis', 'pemasukan')->sum('nominal');
        $total_pengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('nominal');
        $saldo_akhir = $total_pemasukan - $total_pengeluaran;

        return view('jemaat.keuangan', compact(
            'transaksis',
            'total_pemasukan',
            'total_pengeluaran',
            'saldo_akhir'
        ));
    }
}