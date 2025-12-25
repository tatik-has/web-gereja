<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan; // Pastikan model Keuangan diimpor

class KeuanganController extends Controller
{
    /**
     * Tampilkan laporan Keuangan (Hanya Baca/Read) untuk Pendeta.
     */
    public function index()
    {
        // Berdasarkan Controller Jemaat Anda, kita asumsikan kolom uang adalah 'nominal'
        $kolom_jumlah = 'nominal'; 
        
        // 1. Ambil data transaksi dengan pagination (Diurutkan berdasarkan tanggal terbaru)
        $keuangans = Keuangan::orderBy('tanggal', 'desc')->paginate(20);

        // 2. Hitung total pemasukan dan pengeluaran
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum($kolom_jumlah);
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum($kolom_jumlah);
        
        // Catatan: Variabel $saldo_akhir tidak diperlukan di compact karena bisa dihitung di view.
        // Namun, jika Anda ingin menyertakannya:
        // $saldo_akhir = $totalPemasukan - $totalPengeluaran; 

        // 3. Kirim data ke view pendeta.keuangan.index
        return view('pendeta.keuangan.index', compact(
            'keuangans', // Digunakan untuk tabel detail transaksi
            'totalPemasukan', // Digunakan untuk kartu statistik
            'totalPengeluaran' // Digunakan untuk kartu statistik
        ));
    }
}