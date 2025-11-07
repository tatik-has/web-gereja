@extends('layouts.app')

@section('title', 'Manajemen Anggota Keluarga: ' . $jemaat->nama)

@section('content')
    <a href="{{ route('admin.jemaats.index') }}" class="btn btn-secondary" style="margin-bottom: 20px;">
        &larr; Kembali ke Daftar Jemaat
    </a>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
        <div>
            <h3>Tambah Anggota Baru</h3>
            <form action="{{ route('admin.anggota.store', $jemaat) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_anggota">Nama Lengkap</label>
                    <input type="text" id="nama_anggota" name="nama_anggota" class="form-control" value="{{ old('nama_anggota') }}" required>
                </div>
                <div class="form-group">
                    <label for="status_hubungan">Status Hubungan</label>
                    <select id="status_hubungan" name="status_hubungan" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Istri">Istri</option>
                        <option value="Suami">Suami</option>
                        <option value="Anak">Anak</option>
                        <option value="Cucu">Cucu</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="usia">Usia (Tahun)</label>
                    <input type="number" id="usia" name="usia" class="form-control" value="{{ old('usia') }}">
                </div>
                <div class="form-group">
                    <label for="pekerjaan">Pekerjaan</label>
                    <input type="text" id="pekerjaan" name="pekerjaan" class="form-control" value="{{ old('pekerjaan') }}">
                </div>
                <button type="submit" class="btn btn-primary">Tambah Anggota</button>
            </form>
        </div>

        <div>
            <h3>Daftar Anggota ({{ $jemaat->nama }})</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Hubungan</th>
                        <th>Usia</th>
                        <th>Pekerjaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggotas as $anggota)
                    <tr>
                        <td>{{ $anggota->nama_anggota }}</td>
                        <td>{{ $anggota->status_hubungan }}</td>
                        <td>{{ $anggota->usia }}</td>
                        <td>{{ $anggota->pekerjaan }}</td>
                        <td>
                            <a href="{{ route('admin.anggota.edit', $anggota) }}" class="btn btn-secondary" style="padding: 5px 10px;">Edit</a>
                            <form action="{{ route('admin.anggota.destroy', $anggota) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus anggota ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background-color:#e74c3c; color:white; padding: 5px 10px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Belum ada anggota keluarga.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection