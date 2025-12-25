<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PendetaController extends Controller
{
    // Tampilkan daftar pendeta (hanya admin yang bisa akses)
    public function index()
    {
        $pendetas = Admin::where('role', 'pendeta')->latest()->paginate(20);
        return view('admin.pendeta_index', compact('pendetas'));
    }

    // Form tambah pendeta
    public function create()
    {
        return view('admin.pendeta_create');
    }

    // Simpan pendeta baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
        ]);
        
       Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pendeta', // PENTING: role sudah diset 'pendeta'
        ]);
        
        return redirect()->route('admin.pendeta.index')->with('success', 'Pendeta berhasil ditambahkan.');
    }

    // Form edit pendeta
    public function edit($id)
    {
        $pendeta = Admin::where('role', 'pendeta')->findOrFail($id);
        return view('admin.pendeta_create', compact('pendeta'));
    }

    // Update data pendeta
    public function update(Request $request, $id)
    {
        $pendeta = Admin::where('role', 'pendeta')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('admins')->ignore($pendeta->id)],
        ]);
        
        $pendeta->update($request->only('name', 'email'));
        
        // Update password jika diisi
        if ($request->filled('password')) {
            $pendeta->password = Hash::make($request->password);
            $pendeta->save();
        }
        
        return back()->with('success', 'Data pendeta berhasil diperbarui.');
    }

    // Hapus pendeta
    public function destroy($id)
    {
        $pendeta = Admin::where('role', 'pendeta')->findOrFail($id);
        $pendeta->delete();
        
        return redirect()->route('admin.pendeta.index')->with('success', 'Pendeta berhasil dihapus.');
    }
}