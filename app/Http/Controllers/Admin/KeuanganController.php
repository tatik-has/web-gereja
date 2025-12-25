<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KeuanganController extends Controller
{
    public function index()
    {
        $transaksis = Keuangan::orderBy('tanggal', 'desc')->paginate(20);
        
        // === HITUNG SALDO PER KATEGORI ===
        
        // 1. PEMASUKAN PER KATEGORI
        $persembahan_umum = Keuangan::where('jenis', 'pemasukan')
                                     ->where('kategori', 'persembahan_umum')
                                     ->sum('nominal');
        
        $ucapan_syukur = Keuangan::where('jenis', 'pemasukan')
                                  ->where('kategori', 'ucapan_syukur')
                                  ->sum('nominal');
        
        $persepuluhan = Keuangan::where('jenis', 'pemasukan')
                                 ->where('kategori', 'persepuluhan')
                                 ->sum('nominal');
        
        // 2. TOTAL PENGELUARAN (Hanya diambil dari Persembahan Umum)
        $total_pengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('nominal');
        
        // 3. SALDO PERSEMBAHAN UMUM (Setelah dikurangi pengeluaran)
        $saldo_persembahan_umum = $persembahan_umum - $total_pengeluaran;
        
        // 4. TOTAL PEMASUKAN KESELURUHAN
        $total_pemasukan = $persembahan_umum + $ucapan_syukur + $persepuluhan;
        
        // 5. SALDO AKHIR KESELURUHAN
        $saldo_akhir = $total_pemasukan - $total_pengeluaran;

        return view('admin.keuangan.index', compact(
            'transaksis', 
            'persembahan_umum',
            'ucapan_syukur',
            'persepuluhan',
            'total_pengeluaran',
            'saldo_persembahan_umum',
            'total_pemasukan',
            'saldo_akhir'
        ));
    }

    public function create()
    {
        return view('admin.keuangan.create');
    }

    public function store(Request $request)
    {
        // === VALIDASI ===
        $rules = [
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'file_bukti' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ];

        // Jika pemasukan, kategori wajib diisi
        if ($request->jenis == 'pemasukan') {
            $rules['kategori'] = 'required|in:persembahan_umum,ucapan_syukur,persepuluhan,lainnya';
        }

        $request->validate($rules);

        // === VALIDASI KHUSUS: Pengeluaran hanya bisa jika Persembahan Umum cukup ===
        if ($request->jenis == 'pengeluaran') {
            $persembahan_umum = Keuangan::where('jenis', 'pemasukan')
                                         ->where('kategori', 'persembahan_umum')
                                         ->sum('nominal');
            
            $total_pengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('nominal');
            
            $saldo_persembahan_umum = $persembahan_umum - $total_pengeluaran;

            if ($request->nominal > $saldo_persembahan_umum) {
                return back()->withErrors([
                    'nominal' => 'Saldo Persembahan Umum tidak cukup! Saldo tersedia: Rp ' . number_format($saldo_persembahan_umum, 0, ',', '.')
                ])->withInput();
            }
        }

        // === HANDLE UPLOAD FILE BUKTI ===
        $fileBuktiPath = null;
        if ($request->hasFile('file_bukti')) {
            $file = $request->file('file_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $fileBuktiPath = $file->storeAs('bukti_keuangan', $filename, 'public');
        }

        // === SIMPAN DATA ===
        Keuangan::create([
            'jenis' => $request->jenis,
            'kategori' => $request->jenis == 'pemasukan' ? $request->kategori : 'lainnya',
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'file_bukti' => $fileBuktiPath,
        ]);

        return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function edit(Keuangan $keuangan)
    {
        return view('admin.keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        // === VALIDASI ===
        $rules = [
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'file_bukti' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ];

        if ($request->jenis == 'pemasukan') {
            $rules['kategori'] = 'required|in:persembahan_umum,ucapan_syukur,persepuluhan,lainnya';
        }

        $request->validate($rules);

        // === VALIDASI PENGELUARAN (Sama seperti store) ===
        if ($request->jenis == 'pengeluaran') {
            $persembahan_umum = Keuangan::where('jenis', 'pemasukan')
                                         ->where('kategori', 'persembahan_umum')
                                         ->sum('nominal');
            
            // Hitung pengeluaran TANPA transaksi ini (karena sedang edit)
            $total_pengeluaran = Keuangan::where('jenis', 'pengeluaran')
                                          ->where('id', '!=', $keuangan->id)
                                          ->sum('nominal');
            
            $saldo_persembahan_umum = $persembahan_umum - $total_pengeluaran;

            if ($request->nominal > $saldo_persembahan_umum) {
                return back()->withErrors([
                    'nominal' => 'Saldo Persembahan Umum tidak cukup! Saldo tersedia: Rp ' . number_format($saldo_persembahan_umum, 0, ',', '.')
                ])->withInput();
            }
        }

        // === HANDLE UPLOAD FILE BARU ===
        if ($request->hasFile('file_bukti')) {
            // Hapus file lama jika ada
            if ($keuangan->file_bukti && Storage::disk('public')->exists($keuangan->file_bukti)) {
                Storage::disk('public')->delete($keuangan->file_bukti);
            }
            
            $file = $request->file('file_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $keuangan->file_bukti = $file->storeAs('bukti_keuangan', $filename, 'public');
        }

        // === UPDATE DATA ===
        $keuangan->update([
            'jenis' => $request->jenis,
            'kategori' => $request->jenis == 'pemasukan' ? $request->kategori : 'lainnya',
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
        ]);

        $keuangan->save();

        return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Keuangan $keuangan)
    {
        // Hapus file bukti jika ada
        if ($keuangan->file_bukti && Storage::disk('public')->exists($keuangan->file_bukti)) {
            Storage::disk('public')->delete($keuangan->file_bukti);
        }

        $keuangan->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}