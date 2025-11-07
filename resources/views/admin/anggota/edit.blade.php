@extends('layouts.app')

@section('title', 'Edit Anggota: ' . $anggota->nama_anggota)

@section('content')
    <h3>Edit Data Anggota</h3>
    <p>Kepala Keluarga: <strong>{{ $anggota->jemaat->nama }}</strong></p>

    <form action="{{ route('admin.anggota.update', $anggota) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="nama_anggota">Nama Lengkap</label>
            <input type="text" id="nama_anggota" name="nama_anggota" class="form-control" value="{{ old('nama_anggota', $anggota->nama_anggota) }}" required>
        </div>
        <div class="form-group">
            <label for="status_hubungan">Status Hubungan</label>
            <select id="status_hubungan" name="status_hubungan" class="form-control" required>
                <option value="Istri" {{ $anggota->status_hubungan == 'Istri' ? 'selected' : '' }}>Istri</option>
                <option value="Suami" {{ $anggota->status_hubungan == 'Suami' ? 'selected' : '' }}>Suami</option>
                <option value="Anak" {{ $anggota->status_hubungan == 'Anak' ? 'selected' : '' }}>Anak</option>
                <option value="Cucu" {{ $anggota->status_hubungan == 'Cucu' ? 'selected' : '' }}>Cucu</option>
                <option value="Lainnya" {{ $anggota->status_hubungan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>
        <div class="form-group">
            <label for="usia">Usia (Tahun)</label>
            <input type="number" id="usia" name="usia" class="form-control" value="{{ old('usia', $anggota->usia) }}">
        </div>
        <div class="form-group">
            <label for="pekerjaan">Pekerjaan</label>
            <input type="text" id="pekerjaan" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $anggota->pekerjaan) }}">
        </div>
        
        <button type="submit" class="btn btn-primary">Update Data</button>
        <a href="{{ route('admin.anggota.index', $anggota->jemaat_id) }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection