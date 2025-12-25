<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use App\Models\Jemaat;

class JemaatController extends Controller
{
    /**
     * Tampilkan daftar Jemaat (Read-Only untuk Pendeta)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jemaats = Jemaat::withCount('anggotaKeluarga')
                         ->latest()
                         ->paginate(20);
        
        return view('pendeta.jemaat.index', compact('jemaats'));
    }

    /**
     * Tampilkan detail Jemaat beserta Anggota Keluarga (Read-Only)
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $jemaat = Jemaat::with('anggotaKeluarga')->findOrFail($id);
        
        return view('pendeta.jemaat.show', compact('jemaat'));
    }
}