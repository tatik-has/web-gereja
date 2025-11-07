<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        $transaksis = Keuangan::orderBy('tanggal', 'desc')->paginate(20);
        
        // Hitung Saldo
        $total_pemasukan = Keuangan::where('jenis', 'pemasukan')->sum('nominal');
        $total_pengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('nominal');
        $saldo_akhir = $total_pemasukan - $total_pengeluaran;

        return view('admin.keuangan.index', compact(
            'transaksis', 'total_pemasukan', 'total_pengeluaran', 'saldo_akhir'
        ));
    }

    public function create()
    {
        return view('admin.keuangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        Keuangan::create($request->all());

        return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    // Fungsi show() tidak kita pakai, bisa dihapus
    public function show(Keuangan $keuangan)
    {
        return redirect()->route('admin.keuangan.index');
    }

    public function edit(Keuangan $keuangan)
    {
        return view('admin.keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $keuangan->update($request->all());

        return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Keuangan $keuangan)
    {
        $keuangan->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}