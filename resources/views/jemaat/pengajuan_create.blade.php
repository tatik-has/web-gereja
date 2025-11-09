@extends('layouts.jemaat')
@section('title', 'Buat Pengajuan Bantuan')

@section('content')

{{-- Header Card --}}
<div class="form-header-card">
    <div class="form-header-icon">
        <i class="fa-solid fa-file-pen"></i>
    </div>
    <div class="form-header-text">
        <h2>Buat Pengajuan Bantuan</h2>
        <p>Silakan isi formulir di bawah ini untuk mengajukan permohonan bantuan. Pastikan semua data yang Anda berikan adalah benar dan akurat.</p>
    </div>
</div>

{{-- Form Card --}}
<div class="form-card">
    <form action="{{ route('jemaat.pengajuan.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="judul">
                <i class="fa-solid fa-heading"></i> Judul Pengajuan
                <span class="required">*</span>
            </label>
            <input 
                type="text" 
                id="judul" 
                name="judul" 
                value="{{ old('judul') }}" 
                placeholder="Contoh: Permohonan Bantuan Biaya Berobat" 
                required
                class="form-input">
            <small class="form-hint">Berikan judul yang jelas dan deskriptif</small>
        </div>
        
        <div class="form-group">
            <label for="musibah_id">
                <i class="fa-solid fa-list"></i> Jenis Musibah/Bantuan
                <span class="required">*</span>
            </label>
            <select name="musibah_id" id="musibah_id" required class="form-select">
                <option value="">-- Pilih Jenis --</option>
                @foreach($musibahs as $musibah)
                    <option value="{{ $musibah->id }}" {{ old('musibah_id') == $musibah->id ? 'selected' : '' }}>
                        {{ $musibah->nama }} - {{ $musibah->keterangan }}
                    </option>
                @endforeach
            </select>
            <small class="form-hint">Pilih jenis bantuan yang sesuai dengan kebutuhan Anda</small>
        </div>

        <div class="form-group">
            <label for="keterangan">
                <i class="fa-solid fa-comment-dots"></i> Keterangan Tambahan
                <span class="required">*</span>
            </label>
            <textarea 
                name="keterangan" 
                id="keterangan" 
                rows="6" 
                placeholder="Jelaskan kondisi Anda atau detail yang diperlukan...&#10;&#10;Contoh:&#10;- Kronologi kejadian&#10;- Kebutuhan bantuan yang diharapkan&#10;- Kondisi keuangan saat ini"
                required
                class="form-textarea">{{ old('keterangan') }}</textarea>
            <small class="form-hint">Jelaskan secara detail agar permohonan Anda dapat diproses dengan baik</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
            </button>
            <a href="{{ route('jemaat.dashboard') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

{{-- Info Card --}}
<div class="info-card">
    <div class="info-icon">
        <i class="fa-solid fa-circle-info"></i>
    </div>
    <div class="info-content">
        <h4>Informasi Penting</h4>
        <ul>
            <li>Pengajuan akan diproses dalam 1-3 hari kerja</li>
            <li>Anda akan mendapat notifikasi melalui sistem ini</li>
            <li>Pastikan data yang diisi lengkap dan benar</li>
            <li>Untuk pertanyaan lebih lanjut, hubungi sekretariat gereja</li>
        </ul>
    </div>
</div>

@endsection