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
        $anggotas = $jemaat->anggotaKeluarga()->get();
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
        $jemaat->anggotaKeluarga()->create($request->all());

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

        // Redirect kembali ke halaman index anggota
        return redirect()->route('admin.anggota.index', $anggota->jemaat_id)
                         ->with('success', 'Data anggota berhasil diperbarui.');
    }

    // Menghapus anggota
    public function destroy(AnggotaKeluarga $anggota)
    {
        $anggota->delete();
        return back()->with('success', 'Anggota keluarga berhasil dihapus.');
    }
}