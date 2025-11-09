@extends('layouts.admin')

@section('title', 'Edit Pengumuman')

@section('content')
    <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="judul">Judul Pengumuman</label>
            <input type="text" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal (Opsional)</label>
            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $pengumuman->tanggal) }}">
        </div>

        <div class="form-group">
            <label for="isi">Isi Pengumuman</label>
            <textarea id="isi" name="isi" rows="10" required>{{ old('isi', $pengumuman->isi) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Pengumuman</button>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection