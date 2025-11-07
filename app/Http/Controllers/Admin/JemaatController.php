<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use Illuminate\Support\Facades\Hash;

class JemaatController extends Controller
{
    public function index()
    {
        $jemaats = Jemaat::withCount('anggotaKeluarga')->paginate(20);
        return view('admin.jemaat_index', compact('jemaats'));
    }

    public function create()
    {
        return view('admin.jemaat_create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama' => 'required',
            'email' => 'nullable|email|unique:jemaats',
            'password' => 'required|min:6',
            'gaji_per_bulan' => 'nullable|numeric', // Tambah validasi
        ]);
        
        $jemaat = Jemaat::create([
            'nama' => $r->nama,
            'email' => $r->email,
            'no_hp' => $r->no_hp,
            'alamat' => $r->alamat,
            'pekerjaan' => $r->pekerjaan,
            'gaji_per_bulan' => $r->gaji_per_bulan ?? 0, // TAMBAH INI
            'usia' => $r->usia,
            // 'tanggungan' => $r->tanggungan ?? 0, // HAPUS BARIS INI
            'password' => Hash::make($r->password),
            'approved' => $r->has('approved') ? true : false,
            'status_sosial' => $r->status_sosial ?? 'Umum' // TAMBAH INI
        ]);

        return redirect()->route('admin.jemaats.index')->with('success', 'Jemaat ditambahkan');
    }

    public function edit($id)
    {
        $jemaat = Jemaat::findOrFail($id);
        return view('admin.jemaat_create', compact('jemaat'));
    }

    public function update(Request $r, $id)
    {
        $jemaat = Jemaat::findOrFail($id);
        
        // UPDATE BARIS INI: Hapus 'tanggungan', tambahkan field baru
        $jemaat->update($r->only(
            'nama', 'email', 'no_hp', 'alamat', 'pekerjaan', 
            'gaji_per_bulan', 'usia', 'status_sosial'
        ));
        
        if ($r->filled('password'))
            $jemaat->password = Hash::make($r->password);
        
        $jemaat->approved = $r->has('approved');
        $jemaat->save();
        return back()->with('success', 'Data tersimpan');
    }
}