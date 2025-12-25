<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use App\Models\AnggotaKeluarga; // Perlu diimpor jika ingin melihat detail anggota

class JemaatController extends Controller
{
    /**
     * Tampilkan daftar Jemaat (Hanya Baca/Read).
     */
    public function index()
    {
        // Ambil data Jemaat, hanya tampilkan, tidak ada aksi CRUD
        $jemaats = Jemaat::withCount('anggotaKeluarga')->latest()->paginate(20);
        return view('pendeta.jemaat.index', compact('jemaats'));
    }

    /**
     * Tampilkan detail Jemaat (Hanya Baca/Read).
     */
    public function show($id)
    {
        $jemaat = Jemaat::with('anggotaKeluarga')->findOrFail($id);
        return view('pendeta.jemaat.show', compact('jemaat'));
        // Anda perlu membuat view 'pendeta.jemaat.show'
    }
}