<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PerhitunganSmart; // Penting untuk menyimpan hasil

class PerhitunganController extends Controller
{
    /**
     * Fungsi untuk menjalankan perhitungan SMART.
     * Ini akan dipanggil oleh form dari halaman 'pengajuan_show'.
     */
    public function hitung(Request $request)
    {
        // 1. Validasi request
        $request->validate(['pengajuan_id' => 'required|exists:pengajuans,id']);

        // 2. Ambil ID pengajuan dari form
        $pengajuan_id = $request->input('pengajuan_id');
        
        // Eager load jemaat karena kita butuh data jemaat untuk menghitung
        $pengajuan = Pengajuan::with('jemaat')->findOrFail($pengajuan_id);

        // --- Pastikan jemaat ada ---
        if (!$pengajuan->jemaat) {
            return redirect()->back()->withErrors(['error' => 'Data Jemaat tidak ditemukan untuk pengajuan ini.']);
        }

        // 3. --- LAKUKAN LOGIKA PERHITUNGAN SMART ANDA DI SINI ---
        //
        //    Ambil data dari:
        //    $pengajuan->jemaat->gaji_per_bulan
        //    $pengajuan->jemaat->usia
        //    $pengajuan->jemaat->tanggungan
        //    $pengajuan->jemaat->status_sosial
        //    $pengajuan->musibah_id (mungkin ini jadi C1 - Kondisi Darurat)
        //
        //    ...
        //    Lakukan normalisasi, hitung utilitas, dan skor akhir...
        //
        //    (Ini HANYA CONTOH data palsu, ganti dengan logika Anda)
        //
        $skor_akhir = 0.8750; // <-- Ganti dengan HASIL perhitungan Anda
        $nilai_kriteria_json = [
            'C1' => 80, // <-- Ganti dengan HASIL perhitungan Anda (cth: Nilai Utilitas Musibah)
            'C2' => 90, // <-- Ganti dengan HASIL perhitungan Anda (cth: Nilai Utilitas Gaji)
            'C3' => 75, // <-- Ganti dengan HASIL perhitungan Anda (cth: Nilai Utilitas Usia)
            'C4' => 85, // <-- Ganti dengan HASIL perhitungan Anda (cth: Nilai Utilitas Status Sosial)
            'C5' => 100, // <-- Ganti dengan HASIL perhitungan Anda (cth: Nilai Utilitas Tanggungan)
        ];
        // --------------------------------------------------------


        // 4. Simpan hasil perhitungan ke database
        // Gunakan updateOrCreate agar tidak duplikat jika dihitung ulang
        PerhitunganSmart::updateOrCreate(
            ['pengajuan_id' => $pengajuan->id], // Cari berdasarkan ini
            [
                'total_score' => $skor_akhir,
                'nilai_per_kriteria' => $nilai_kriteria_json
            ] // Simpan/Update data ini
        );

        // 5. Kembalikan user ke halaman detail pengajuan
        return redirect()->route('admin.pengajuan.show', $pengajuan->id)
                         ->with('success', 'Perhitungan SMART berhasil disimpan.');
    }

    /**
     * Menampilkan halaman index Perhitungan (jika ada)
     */
    public function index()
    {
    
        
        $hasil_perhitungan = PerhitunganSmart::with('pengajuan.jemaat')
                                ->orderBy('total_score', 'desc')
                                ->get();

        return view('admin.perhitungan_index', compact('hasil_perhitungan'));
    }
}