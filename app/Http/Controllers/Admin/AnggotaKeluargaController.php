<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jemaat;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;

class AnggotaKeluargaController extends Controller
{
    // Menampilkan daftar anggota & form 'tambah'
    public function index(Jemaat $jemaat)
    {
        // Ambil semua anggota keluarga milik jemaat ini
        $anggotas = $jemaat->anggotaKeluarga()->latest()->get();
        return view('admin.anggota.index', compact('jemaat', 'anggotas'));
    }

    // Menyimpan anggota baru
    public function store(Request $request, Jemaat $jemaat)
    {
        $request->validate([
            'nama_anggota' => 'required|string|max:255',
            'status_hubungan' => 'required|string|max:100',
            'usia' => 'nullable|integer|min:0',
            'pekerjaan' => 'nullable|string|max:255',
        ]);

        // Buat anggota baru yang terhubung langsung ke jemaat
        // Menggunakan $request->all() seperti kode asli Anda
        $jemaat->anggotaKeluarga()->create($request->all());

        // === LOGIKA OTOMATIS ANDA DITAMBAHKAN DI SINI ===
        $this->updateTanggunganCount($jemaat);
        // ===============================================

        return back()->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(AnggotaKeluarga $anggota)
    {
        return view('admin.anggota.edit', compact('anggota'));
    }

    // Mengupdate anggota
    public function update(Request $request, AnggotaKeluarga $anggota)
    {
        $request->validate([
            'nama_anggota' => 'required|string|max:255',
            'status_hubungan' => 'required|string|max:100',
            'usia' => 'nullable|integer|min:0',
            'pekerjaan' => 'nullable|string|max:255',
        ]);

        $anggota->update($request->all());

        // Update tidak mengubah jumlah, jadi tidak perlu panggil updateTanggunganCount()
        
        return redirect()->route('admin.anggota.index', $anggota->jemaat_id)
                         ->with('success', 'Data anggota berhasil diperbarui.');
    }

    // Menghapus anggota
    public function destroy(AnggotaKeluarga $anggota)
    {
        // Ambil Jemaat-nya dulu SEBELUM dihapus
        $jemaat = $anggota->jemaat;

        $anggota->delete();

        // === LOGIKA OTOMATIS ANDA DITAMBAHKAN DI SINI ===
        $this->updateTanggunganCount($jemaat);
        // ===============================================

        return back()->with('success', 'Anggota keluarga berhasil dihapus.');
    }

    /**
     * FUNGSI HELPER UNTUK MENGHITUNG OTOMATIS
     * Menghitung total anggota dan menyimpannya ke tabel Jemaat.
     */
    private function updateTanggunganCount(Jemaat $jemaat)
    {
        // 1. Hitung total anggota yang dimiliki jemaat ini
        $count = $jemaat->anggotaKeluarga()->count();
        
        // 2. Simpan angka tersebut ke kolom 'jumlah_tanggungan' di tabel 'jemaats'
        $jemaat->jumlah_tanggungan = $count;
        $jemaat->save();
    }
}