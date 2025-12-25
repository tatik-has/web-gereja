<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JemaatController extends Controller
{
    public function index()
    {
        $jemaats = Jemaat::withCount('anggotaKeluarga')->latest()->paginate(20);
        return view('admin.jemaat_index', compact('jemaats'));
    }

    public function create()
    {
        return view('admin.jemaat_create');
    }

    public function store(Request $r)
    {
        // === VALIDASI DIPERBAIKI + TAMBAH FILE_KK ===
        $r->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:jemaats,email',
            'password' => 'required|min:6',
            'gaji_per_bulan' => 'required|numeric|min:0',
            'pekerjaan' => 'required|string',
            'status_sosial' => 'required|string',
            'usia' => 'required|integer|min:1',
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'file_kk' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048', // <<< TAMBAH INI (max 2MB)
        ]);
        
        // === HANDLE UPLOAD FILE ===
        $fileKkPath = null;
        if ($r->hasFile('file_kk')) {
            $file = $r->file('file_kk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $fileKkPath = $file->storeAs('kartu_keluarga', $filename, 'public');
        }
        
        $jemaat = Jemaat::create([
            'nama' => $r->nama,
            'email' => $r->email,
            'no_hp' => $r->no_hp,
            'alamat' => $r->alamat,
            'pekerjaan' => $r->pekerjaan,
            'gaji_per_bulan' => $r->gaji_per_bulan,
            'usia' => $r->usia,
            'jumlah_tanggungan' => $r->jumlah_tanggungan,
            'status_sosial' => $r->status_sosial,
            'password' => Hash::make($r->password),
            'approved' => $r->has('approved') ? true : false,
            'file_kk' => $fileKkPath, // <<< SIMPAN PATH FILE
        ]);

        return redirect()->route('admin.jemaats.index')->with('success', 'Jemaat baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jemaat = Jemaat::findOrFail($id);
        return view('admin.jemaat_create', compact('jemaat'));
    }

    public function update(Request $r, $id)
    {
        $jemaat = Jemaat::findOrFail($id);
        
        // === VALIDASI UPDATE + FILE_KK ===
        $r->validate([
            'nama' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('jemaats')->ignore($jemaat->id)],
            'gaji_per_bulan' => 'required|numeric|min:0',
            'pekerjaan' => 'required|string',
            'status_sosial' => 'required|string',
            'usia' => 'required|integer|min:1',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'file_kk' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048', // <<< TAMBAH INI
        ]);
        
        // === HANDLE UPLOAD FILE BARU ===
        if ($r->hasFile('file_kk')) {
            // Hapus file lama jika ada
            if ($jemaat->file_kk && Storage::disk('public')->exists($jemaat->file_kk)) {
                Storage::disk('public')->delete($jemaat->file_kk);
            }
            
            // Upload file baru
            $file = $r->file('file_kk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $jemaat->file_kk = $file->storeAs('kartu_keluarga', $filename, 'public');
        }
        
        // UPDATE DATA
        $jemaat->update($r->only(
            'nama', 'email', 'no_hp', 'alamat', 'pekerjaan', 
            'gaji_per_bulan', 'usia', 'status_sosial',
            'jumlah_tanggungan'
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
        $jemaat = Jemaat::findOrFail($id);
        
        // === HAPUS FILE KK JIKA ADA ===
        if ($jemaat->file_kk && Storage::disk('public')->exists($jemaat->file_kk)) {
            Storage::disk('public')->delete($jemaat->file_kk);
        }
        
        $jemaat->delete();
        
        return redirect()->route('admin.jemaats.index')
                         ->with('success', 'Data jemaat berhasil dihapus.');
    }
}