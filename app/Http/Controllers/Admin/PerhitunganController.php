<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PerhitunganSmart;

class PerhitunganController extends Controller
{
    // Bobot Kriteria (Total 1.00 atau 100%)
    private $bobot = [
        'C1' => 0.30, // Kondisi Darurat/Musibah
        'C2' => 0.25, // Pekerjaan/Penghasilan
        'C3' => 0.15, // Usia
        'C4' => 0.15, // Status Sosial
        'C5' => 0.15, // Jumlah Tanggungan
    ];

    // Nilai Maksimum untuk setiap kriteria (Berdasarkan Tabel 3.2 - 3.6)
    private $nilai_max = [
        'C1' => 30,
        'C2' => 30,
        'C3' => 30,
        'C4' => 30,
        'C5' => 30,
    ];

    // Nilai Minimum untuk setiap kriteria (Berdasarkan Tabel 3.2 - 3.6)
    private $nilai_min = [
        'C1' => 0,
        'C2' => 0,
        'C3' => 0,
        'C4' => 0,
        'C5' => 0,
    ];

    public function hitung(Request $request)
    {
        $request->validate(['pengajuan_id' => 'required|exists:pengajuans,id']);

        $pengajuan_id = $request->input('pengajuan_id');
        $pengajuan = Pengajuan::with('jemaat', 'musibah')->findOrFail($pengajuan_id);

        if (!$pengajuan->jemaat) {
            return redirect()->back()->withErrors(['error' => 'Data Jemaat tidak ditemukan untuk pengajuan ini.']);
        }

        \Log::info('=== PERHITUNGAN DIMULAI (VERSI FINAL) ===');
        \Log::info('Pengajuan ID: ' . $pengajuan->id);

        // Tahap 1: Hitung Nilai Mentah (Sesuai Tabel 3.9)
        $nilai_mentah = $this->hitungNilaiMentah($pengajuan);

        // Tahap 2: Hitung Nilai Utility (Sesuai Tabel 3.10 & 3.11)
        $nilai_utility = $this->hitungUtility($nilai_mentah);

        // Tahap 3: Hitung Skor Akhir (Sesuai Tabel 3.12 - 3.24)
        $skor_akhir = $this->hitungSkorAkhir($nilai_utility);

        // Tahap 4: Tentukan Kelayakan (Sesuai Tabel 3.25)
        $hasil = $this->tentukanKelayakan($skor_akhir, $nilai_mentah, $pengajuan);

        // Debugging
        \Log::info('Nilai Mentah (VERSI FINAL):', $nilai_mentah);
        \Log::info('Nilai Utility (VERSI FINAL):', $nilai_utility);
        \Log::info('Skor Akhir (VERSI FINAL): ' . $skor_akhir);
        \Log::info('Hasil (VERSI FINAL):', $hasil);
        \Log::info('=== PERHITUNGAN SELESAI (VERSI FINAL) ===');

        // Simpan hasil perhitungan ke database
        PerhitunganSmart::updateOrCreate(
            ['pengajuan_id' => $pengajuan->id],
            [
                'total_score' => $skor_akhir,
                'nilai_per_kriteria' => $nilai_utility, // Simpan utility untuk transparansi
                'kategori' => $hasil['kategori'],
                'alasan' => $hasil['alasan'],
                'rekomendasi' => $hasil['rekomendasi']
            ]
        );

        return redirect()->route('admin.pengajuan.show', $pengajuan->id)
            ->with('success', 'Perhitungan SMART berhasil disimpan.')
            ->with('hasil', $hasil);
    }

    /**
     * Tahap 1: Mendapatkan nilai mentah (0-30) untuk setiap kriteria.
     */
    private function hitungNilaiMentah($pengajuan)
    {
        $jemaat = $pengajuan->jemaat;
        $nilai = [];

        \Log::info('Mulai hitungNilaiMentah...');
        $nilai['C1'] = $this->getNilaiMusibah($pengajuan->musibah->nama ?? null);

        // ==================================================
        // === PERUBAHAN 1: NAMA KOLOM GAJI DIPERBAIKI ===
        // ==================================================
        $nilai['C2'] = $this->getNilaiPenghasilan($jemaat->pekerjaan, $jemaat->gaji_per_bulan);
        // ==================================================

        $nilai['C3'] = $this->getNilaiUsia($jemaat->usia);
        $nilai['C4'] = $this->getNilaiStatusSosial($jemaat->status_sosial);
        $nilai['C5'] = $this->getNilaiTanggungan($jemaat->jumlah_tanggungan);
        \Log::info('Selesai hitungNilaiMentah.');

        return $nilai;
    }

    /**
     * Tahap 2: Menghitung nilai utility (0-100)
     */
    private function hitungUtility($nilai_mentah)
    {
        $utility = [];
        foreach ($nilai_mentah as $kriteria => $nilai) {
            $c_max = $this->nilai_max[$kriteria];
            $c_min = $this->nilai_min[$kriteria];

            if ($c_max == $c_min) {
                $utility[$kriteria] = 0;
            } else {
                $utility[$kriteria] = 100 * (($nilai - $c_min) / ($c_max - $c_min));
            }
        }
        return $utility;
    }

    /**
     * Tahap 3: Menghitung skor akhir
     */
    private function hitungSkorAkhir($nilai_utility)
    {
        $skor = 0;
        foreach ($nilai_utility as $kriteria => $utility) {
            $skor += $this->bobot[$kriteria] * $utility;
        }
        return round($skor, 2); // Dibulatkan 2 desimal
    }

    /**
     * Tahap 4: Menentukan Kelayakan
     */
    private function tentukanKelayakan($skor, $nilai_mentah, $pengajuan)
    {
        $jemaat = $pengajuan->jemaat;

        // Thresholds (Ambang Batas) berdasarkan data Tabel 3.25
        $kategori = '';
        $rekomendasi = '';
        $alasan = '';

        if ($skor >= 45.00) {
            $kategori = 'Layak';
            $rekomendasi = 'Direkomendasikan';
            $alasan_parts = [];

            if ($nilai_mentah['C1'] >= 20) {
                $alasan_parts[] = "mengalami musibah berat (" . ($pengajuan->musibah->nama ?? 'N/A') . ")";
            }
            if ($nilai_mentah['C2'] >= 25) {
                $alasan_parts[] = "penghasilan sangat rendah";
            }
            if ($nilai_mentah['C4'] >= 25) {
                $alasan_parts[] = "status sosial rentan (" . $jemaat->status_sosial . ")";
            }
            if ($nilai_mentah['C5'] >= 20) {
                $alasan_parts[] = "memiliki banyak tanggungan";
            }
            if ($nilai_mentah['C3'] >= 25) {
                $alasan_parts[] = "usia lanjut";
            }

            if (empty($alasan_parts)) {
                $alasan = "Berdasarkan skor total (" . $skor . "), pemohon memenuhi kriteria untuk mendapatkan bantuan.";
            } else {
                $alasan = "Skor tinggi (" . $skor . "). Prioritas karena " . implode(", ", $alasan_parts) . ".";
            }
        } elseif ($skor >= 28.00 && $skor < 45.00) {
            $kategori = 'Kurang Layak';
            $rekomendasi = 'Tidak Direkomendasikan (Prioritas Rendah)';
            $alasan = "Skor (" . $skor . ") berada di bawah ambang batas prioritas. Kondisi ekonomi dinilai masih cukup stabil atau musibah yang dialami tidak termasuk kriteria utama.";

            if ($nilai_mentah['C2'] <= 15) {
                // ==================================================
                // === PERUBAHAN 2: NAMA KOLOM GAJI DIPERBAIKI ===
                // ==================================================
                $alasan = "Skor (" . $skor . ") tidak memenuhi prioritas. Penghasilan (Rp " . number_format($jemaat->gaji_per_bulan ?? 0, 0, ',', '.') . "/bulan) dinilai masih mencukupi.";
                // ==================================================
            }
        } else {
            $kategori = 'Tidak Layak';
            $rekomendasi = 'Tidak Direkomendasikan';
            $alasan = "Skor (" . $skor . ") sangat rendah. Pemohon dinilai mampu dan tidak memenuhi kriteria darurat untuk bantuan.";
        }

        return [
            'skor' => $skor,
            'kategori' => $kategori,
            'rekomendasi' => $rekomendasi,
            'alasan' => $alasan
        ];
    }

    // ==================================================================
    // FUNGSI HELPER (MAPPER)
    // ==================================================================

    private function getNilaiMusibah($jenis)
    {
        $jenis_clean = strtolower(trim($jenis ?? ''));

        $mapping = [
            'meninggal dunia (keluarga inti)' => 30,
            'meninggal dunia' => 30,
            'kebakaran rumah' => 25,
            'kebakaran' => 25,
            'sakit berat / opname' => 20,
            'sakit berat' => 20,
            'banjir / musibah alam ringan' => 15,
            'banjir' => 15,
            'musibah alam' => 15,
            'sakit ringan / cedera' => 10,
            'sakit ringan' => 10,
            'cedera' => 10,
            'tidak mengalami musibah' => 0,
            'tidak ada' => 0,
            '' => 0
        ];

        $nilai = $mapping[$jenis_clean] ?? 0;
        \Log::info('[DEBUG C1] Input Musibah: "' . $jenis . '", Cleaned: "' . $jenis_clean . '", Output Nilai: ' . $nilai);
        return $nilai;
    }

    // ==================================================
    // === PERUBAHAN 3: FUNGSI getNilaiPenghasilan DISEMPURNAKAN ===
    // ==================================================
    private function getNilaiPenghasilan($pekerjaan, $gaji)
    {
        \Log::info('[DEBUG C2] Input Gaji: ' . $gaji . ' | Input Pekerjaan: "' . $pekerjaan . '"');

        if ($gaji !== null && $gaji > 0) {
            \Log::info('[DEBUG C2] Menghitung berdasarkan ANGKA GAJI: ' . $gaji);
            if ($gaji < 1000000)
                return 30;
            if ($gaji < 2000000)
                return 25;
            if ($gaji < 3000000)
                return 20;
            if ($gaji < 4000000)
                return 15;
            return 10;
        }

        $pekerjaan_clean = strtolower(trim($pekerjaan ?? ''));
        \Log::info('[DEBUG C2] Gaji 0, Menghitung berdasarkan STRING PEKERJAAN: "' . $pekerjaan_clean . '"');

        $mapping = [
            'tidak bekerja' => 30,
            'buruh' => 25,
            'petani' => 25,
            'honor lepas' => 25,
            'irt menanggung sendiri' => 25,
            'umkm kecil' => 20,
            'honorer' => 15,
            'wiraswasta sedang' => 15,
            'swasta' => 15,
            'asn' => 10,
            'pns' => 10,
            'bumn' => 10,
            'tni/polri' => 10,
            'tni' => 10,
            'polri' => 10,
            'ditanggung orang lain' => 0,
            '' => 0
        ];

        return $mapping[$pekerjaan_clean] ?? 0;
    }

    private function getNilaiUsia($usia)
    {
        $usia = (int) $usia;
        if ($usia >= 65)
            return 30;
        if ($usia >= 55)
            return 25;
        if ($usia >= 45)
            return 20;
        if ($usia >= 30)
            return 15;
        if ($usia >= 18)
            return 10;
        return 0;
    }

    private function getNilaiStatusSosial($status)
    {
        $status_clean = strtolower(trim($status ?? ''));

        $mapping = [
            'ibu tunggal' => 30,
            'ayah tunggal' => 25,
            'lansia tinggal sendiri' => 20,
            'dewasa menanggung anggota keluarga lain' => 15,
            'menikah tapi punya banyak tanggungan' => 10,
            'menikah dengan banyak tanggungan' => 10,
            'dewasa tanpa tanggungan' => 0,
            'umum' => 0,
            'mahasiswa' => 5,
            '' => 0
        ];

        // BENAR
        return $mapping[$status_clean] ?? 0;
    }

    private function getNilaiTanggungan($jumlah)
    {
        if (is_string($jumlah)) {
            preg_match('/\d+/', $jumlah, $matches);
            $jumlah = !empty($matches) ? (int) $matches[0] : 0;
        }

        $jumlah = (int) $jumlah;

        if ($jumlah >= 5)
            return 30;
        if ($jumlah == 4)
            return 25;
        if ($jumlah == 3)
            return 20;
        if ($jumlah == 2)
            return 15;
        if ($jumlah == 1)
            return 10;
        return 0;
    }

    /**
     * Halaman untuk menampilkan semua hasil perhitungan yang sudah ada.
     */
    public function index()
    {
        $hasil_perhitungan = PerhitunganSmart::with('pengajuan.jemaat')
            ->orderBy('total_score', 'desc')
            ->get();

        return view('admin.perhitungan_index', compact('hasil_perhitungan'));
    }
}
