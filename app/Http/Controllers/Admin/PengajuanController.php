<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index()
    {
        // Ambil semua pengajuan, urutkan dari yg terbaru
        $pengajuans = Pengajuan::with('jemaat', 'musibah', 'perhitunganSmart')
            ->latest()
            ->paginate(15);

        return view('admin.pengajuan_index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('jemaat', 'musibah', 'perhitunganSmart')
            ->findOrFail($id);

        return view('admin.pengajuan_show', compact('pengajuan'));
    }

    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
        ]);

        // 2. Cari pengajuan
        $pengajuan = Pengajuan::findOrFail($id);

        // 3. Update status
        $pengajuan->status = $request->input('status');
        $pengajuan->save();

        // 4. Siapkan pesan sukses
        $message = $request->input('status') == 'diterima'
            ? 'Pengajuan berhasil DITERIMA.'
            : 'Pengajuan telah DITOLAK.';

        // 5. Kembalikan ke halaman show dengan pesan sukses
        return redirect()
            ->route('admin.pengajuan.show', $pengajuan->id)
            ->with('success', $message);
    }
}
