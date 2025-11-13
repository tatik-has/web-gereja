<?php

namespace App\Http\Controllers\Jemaat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Musibah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanJemaatController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::where('jemaat_id', Auth::id())
                        ->with('musibah')
                        ->latest()
                        ->paginate(10);
        return view('jemaat.pengajuan_index', compact('pengajuans'));
    }

    public function create()
    {
        $musibahs = Musibah::all();
        return view('jemaat.pengajuan_create', compact('musibahs'));
    }

    // 🔥 METHOD STORE YANG SUDAH DIUPDATE
    public function store(Request $r)
    {
        $r->validate([
            'judul' => 'required|string|max:255',
            'musibah_id' => 'required|exists:musibahs,id',
            'keterangan' => 'nullable|string',
            'file_bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048' // Maksimal 2MB
        ], [
            'file_bukti.required' => 'File bukti wajib diupload',
            'file_bukti.mimes' => 'File harus berformat JPG, PNG, atau PDF',
            'file_bukti.max' => 'Ukuran file maksimal 2MB'
        ]);

        // Cek apakah jemaat sudah punya pengajuan 'pending'
        $pendingExists = Pengajuan::where('jemaat_id', Auth::id())
                            ->where('status', 'pending')
                            ->exists();

        if ($pendingExists) {
            return redirect()->route('jemaat.pengajuan.index')
                   ->withErrors(['error' => 'Anda masih memiliki pengajuan yang berstatus Pending. Harap tunggu proses review selesai.']);
        }

        // Handle Upload File
        $filePath = null;
        if ($r->hasFile('file_bukti')) {
            $file = $r->file('file_bukti');
            $filename = 'bukti_' . Auth::id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/bukti_pengajuan', $filename);
            // Ubah path agar bisa diakses via URL
            $filePath = str_replace('public/', '', $filePath);
        }

        Pengajuan::create([
           'jemaat_id' => Auth::id(),
           'judul' => $r->judul,
           'keterangan' => $r->keterangan,
           'musibah_id' => $r->musibah_id,
           'file_bukti' => $filePath,
           'status' => 'pending'
        ]);
        
        return redirect()->route('jemaat.pengajuan.index')->with('success','Pengajuan berhasil dikirim dengan bukti pendukung.');
    }
}