@extends('layouts.app')

@section('title', 'Buat Pengumuman Baru')

@section('content')
    <form action="{{ route('admin.pengumuman.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="judul">Judul Pengumuman</label>
            <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal (Opsional)</label>
            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}">
        </div>

        <div class="form-group">
            <label for="isi">Isi Pengumuman</label>
            <textarea id="isi" name="isi" rows="10" required>{{ old('isi') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Pengumuman</button>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection