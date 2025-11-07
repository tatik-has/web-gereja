@extends('layouts.app')
@section('title', 'Buat Pengajuan Bantuan')

@section('content')
    <p>Silakan isi formulir di bawah ini untuk mengajukan permohonan bantuan.</p>
    
    <form action="{{ route('jemaat.pengajuan.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="judul">Judul Pengajuan</label>
            <input type="text" id="judul" name="judul" value="{{ old('judul') }}" placeholder="cth: Permohonan Bantuan Biaya Berobat" required>
        </div>
        
        <div class="form-group">
            <label for="musibah_id">Jenis Musibah/Bantuan</label>
            <select name="musibah_id" id="musibah_id" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach($musibahs as $musibah)
                    <option value="{{ $musibah->id }}">{{ $musibah->nama }} ({{ $musibah->keterangan }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan Tambahan</label>
            <textarea name="keterangan" id="keterangan" rows="5" placeholder="Jelaskan kondisi Anda atau detail yang diperlukan...">{{ old('keterangan') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    </form>
@endsection