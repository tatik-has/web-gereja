<?php

namespace App\Http\Controllers\Jemaat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Musibah; // Perlu untuk form
use Illuminate\Support\Facades\Auth;

class PengajuanJemaatController extends Controller
{
    // Menampilkan riwayat pengajuan milik Jemaat yang login
    public function index()
    {
        $pengajuans = Pengajuan::where('jemaat_id', Auth::id())
                        ->with('musibah')
                        ->latest()
                        ->paginate(10);
        return view('jemaat.pengajuan_index', compact('pengajuans'));
    }

    // Menampilkan form pembuatan
    public function create()
    {
        $musibahs = Musibah::all(); // Ambil data musibah untuk dropdown
        return view('jemaat.pengajuan_create', compact('musibahs'));
    }

    // Menyimpan pengajuan baru
    public function store(Request $r)
    {
        $r->validate([
            'judul' => 'required|string|max:255',
            'musibah_id' => 'required|exists:musibahs,id',
            'keterangan' => 'nullable|string',
        ]);

        // Cek apakah jemaat sudah punya pengajuan 'pending'
        $pendingExists = Pengajuan::where('jemaat_id', Auth::id())
                            ->where('status', 'pending')
                            ->exists();

        if ($pendingExists) {
            return redirect()->route('jemaat.pengajuan.index')
                   ->withErrors(['error' => 'Anda masih memiliki pengajuan yang berstatus Pending. Harap tunggu proses review selesai.']);
        }

        Pengajuan::create([
           'jemaat_id' => Auth::id(),
           'judul' => $r->judul,
           'keterangan' => $r->keterangan,
           'musibah_id' => $r->musibah_id,
           'status' => 'pending'
        ]);
        
        return redirect()->route('jemaat.pengajuan.index')->with('success','Pengajuan berhasil dikirim.');
    }
}