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

{{-- Alert Error --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Form Card --}}
<div class="form-card">
    <form action="{{ route('jemaat.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
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

        {{-- 🔥 BAGIAN BARU: UPLOAD FILE BUKTI --}}
        <div class="form-group">
            <label for="file_bukti">
                <i class="fa-solid fa-file-upload"></i> Upload Foto/File Surat Pendukung
                <span class="required">*</span>
            </label>
            <input 
                type="file" 
                id="file_bukti" 
                name="file_bukti" 
                accept="image/*,.pdf"
                required
                class="form-input"
                onchange="previewFile(this)">
            <small class="form-hint">
                Format yang diperbolehkan: JPG, PNG, PDF (Maksimal 2MB)<br>
                Contoh: Surat Keterangan Sakit, Foto Kebakaran, Surat Kematian, dll.
            </small>
            
            {{-- Preview Area --}}
            <div id="file-preview" style="margin-top: 10px; display: none;">
                <img id="preview-image" src="" alt="Preview" style="max-width: 300px; max-height: 300px; border: 2px solid #ddd; border-radius: 5px; padding: 5px;">
                <p id="preview-filename" style="margin-top: 5px; font-weight: bold; color: #333;"></p>
            </div>
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
            <li><strong>WAJIB upload file bukti pendukung (foto/surat)</strong></li>
            <li>Untuk pertanyaan lebih lanjut, hubungi sekretariat gereja</li>
        </ul>
    </div>
</div>

{{-- JavaScript untuk Preview File --}}
<script>
function previewFile(input) {
    const preview = document.getElementById('file-preview');
    const previewImage = document.getElementById('preview-image');
    const previewFilename = document.getElementById('preview-filename');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileType = file.type;
        
        previewFilename.textContent = '📄 ' + file.name;
        
        if (fileType.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            previewImage.style.display = 'none';
            preview.style.display = 'block';
            previewFilename.innerHTML = '📄 ' + file.name + '<br><small style="color: #666;">File PDF siap diupload</small>';
        }
    }
}
</script>

@endsection