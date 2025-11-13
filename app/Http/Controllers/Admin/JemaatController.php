<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // <<< TAMBAHKAN INI

class JemaatController extends Controller
{
    public function index()
    {
        // Saya ganti 'paginate(20)' ke 'latest()->paginate(20)' agar data terbaru di atas
        $jemaats = Jemaat::withCount('anggotaKeluarga')->latest()->paginate(20);
        return view('admin.jemaat_index', compact('jemaats'));
    }

    public function create()
    {
        return view('admin.jemaat_create');
    }

    public function store(Request $r)
    {
        // === VALIDASI DIPERBAIKI ===
        $r->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:jemaats,email',
            'password' => 'required|min:6',
            'gaji_per_bulan' => 'required|numeric|min:0', // Wajib diisi
            'pekerjaan' => 'required|string', // Wajib diisi
            'status_sosial' => 'required|string', // Wajib diisi
            'usia' => 'required|integer|min:1', // Wajib diisi, minimal 1 tahun
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);
        
        $jemaat = Jemaat::create([
            'nama' => $r->nama,
            'email' => $r->email,
            'no_hp' => $r->no_hp,
            'alamat' => $r->alamat,
            'pekerjaan' => $r->pekerjaan,
            'gaji_per_bulan' => $r->gaji_per_bulan, // Tidak perlu '?? 0' krn sudah required
            'usia' => $r->usia,
            'jumlah_tanggungan' => $r->jumlah_tanggungan, // <<< TAMBAHKAN INI
            'status_sosial' => $r->status_sosial, // Tidak perlu '?? Umum' krn sudah required
            'password' => Hash::make($r->password),
            'approved' => $r->has('approved') ? true : false,
        ]);

        $data['jumlah_tanggungan'] = 0;

        return redirect()->route('admin.jemaats.index')->with('success', 'Jemaat baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jemaat = Jemaat::findOrFail($id);
        return view('admin.jemaat_create', compact('jemaat')); // Anda mungkin perlu ganti ke 'admin.jemaat_edit'
    }

    public function update(Request $r, $id)
    {
        $jemaat = Jemaat::findOrFail($id);
        
        // === VALIDASI UPDATE DIPERBAIKI ===
        $r->validate([
            'nama' => 'required|string|max:255',
            // Pastikan email unik, tapi abaikan email jemaat ini sendiri
            'email' => ['nullable', 'email', Rule::unique('jemaats')->ignore($jemaat->id)],
            'gaji_per_bulan' => 'required|numeric|min:0',
            'pekerjaan' => 'required|string',
            'status_sosial' => 'required|string',
            'usia' => 'required|integer|min:1',
            'jumlah_tanggungan' => 'required|integer|min:0', // <<< TAMBAHKAN INI
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);
        
        // UPDATE BARIS INI: Tambahkan 'jumlah_tanggungan'
        $jemaat->update($r->only(
            'nama', 'email', 'no_hp', 'alamat', 'pekerjaan', 
            'gaji_per_bulan', 'usia', 'status_sosial',
            'jumlah_tanggungan' // <<< TAMBAHKAN INI
        ));
        
        if ($r->filled('password')) {
            $jemaat->password = Hash::make($r->password);
        }
        
        $jemaat->approved = $r->has('approved');
        $jemaat->save();
        
        return back()->with('success', 'Data jemaat berhasil diperbarui.');
    }
    public function destroy($id)
    {
        // 1. Cari jemaat berdasarkan ID
        $jemaat = Jemaat::findOrFail($id);
        
        // 2. Hapus data jemaat
        // Sebaiknya tambahkan logic di sini jika ada data terkait
        // (misal: anggota keluarga) yang perlu dihapus juga.
        $jemaat->delete();
        
        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.jemaats.index')
                         ->with('success', 'Data jemaat berhasil dihapus.');
    }
}