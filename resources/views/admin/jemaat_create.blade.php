@extends('layouts.admin')

@section('title', isset($jemaat) ? 'Edit Data Jemaat' : 'Tambah Jemaat Baru')

@section('content')
{{-- Tombol Kembali --}}
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.jemaats.index') }}" class="btn btn-secondary">
        &larr; Kembali ke Daftar Jemaat
    </a>
</div>

{{-- PENTING: Tambahkan enctype untuk upload file --}}
<form action="{{ isset($jemaat) ? route('admin.jemaats.update', $jemaat->id) : route('admin.jemaats.store') }}" 
      method="POST" 
      enctype="multipart/form-data">
    
    @csrf
    
    @if(isset($jemaat))
        @method('PUT')
    @endif
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        
        {{-- KOLOM 1: DATA LOGIN & KONTAK --}}
        <div>
            <div class="form-group">
                <label for="nama">Nama Lengkap Jemaat</label>
                <input type="text" id="nama" name="nama" class="form-control" 
                       value="{{ old('nama', $jemaat->nama ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email (Untuk Login)</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="{{ old('email', $jemaat->email ?? '') }}">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="{{ isset($jemaat) ? 'Kosongkan jika tidak diubah' : 'Wajib diisi' }}">
                
                @if(!isset($jemaat))
                    <small style="color: #555;">Password wajib diisi untuk jemaat baru.</small>
                @endif
            </div>

            <div class="form-group">
                <label for="no_hp">No. HP / WhatsApp</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control" 
                       value="{{ old('no_hp', $jemaat->no_hp ?? '') }}">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" rows="3" class="form-control">{{ old('alamat', $jemaat->alamat ?? '') }}</textarea>
            </div>

            {{-- === UPLOAD KARTU KELUARGA === --}}
            <div class="form-group">
                <label for="file_kk">Upload Kartu Keluarga (KK)</label>
                <input type="file" id="file_kk" name="file_kk" class="form-control" 
                       accept=".jpg,.jpeg,.png,.pdf">
                <small class="form-text text-muted">
                    Format: JPG, PNG, atau PDF. Maksimal 2MB.
                </small>
                
                {{-- Tampilkan file yang sudah ada (jika edit) --}}
                @if(isset($jemaat) && $jemaat->file_kk)
                    <div style="margin-top: 10px; padding: 10px; background-color: #f0f0f0; border-radius: 5px;">
                        <strong>File KK Saat Ini:</strong>
                        <div style="margin-top: 5px;">
                            @php
                                $extension = pathinfo($jemaat->file_kk, PATHINFO_EXTENSION);
                            @endphp
                            
                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                {{-- Preview untuk gambar --}}
                                <a href="{{ asset('storage/' . $jemaat->file_kk) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $jemaat->file_kk) }}" 
                                         alt="KK Preview" 
                                         style="max-width: 200px; max-height: 200px; border: 2px solid #ddd; border-radius: 5px;">
                                </a>
                            @else
                                {{-- Link download untuk PDF --}}
                                <a href="{{ asset('storage/' . $jemaat->file_kk) }}" 
                                   target="_blank" 
                                   style="color: #007bff; text-decoration: underline;">
                                    📄 Lihat File PDF
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-group form-check" style="margin-top: 10px;">
                <input type="checkbox" id="approved" name="approved" value="1" 
                       {{ old('approved', $jemaat->approved ?? true) ? 'checked' : '' }} 
                       style="width: auto; height: auto; margin-top: 8px;">
                <label for="approved" style="font-weight: normal; margin-bottom: 0;">
                    Aktifkan Akun (Jemaat bisa login)
                </label>
            </div>
        </div>

        {{-- KOLOM 2: DATA KRITERIA --}}
        <div>
            <div class="form-group">
                <label for="usia">Usia (Tahun)</label>
                <input type="number" id="usia" name="usia" class="form-control" 
                       value="{{ old('usia', $jemaat->usia ?? 0) }}" 
                       required min="0">
                <small class="form-text text-muted">
                    * Usia akan digunakan untuk perhitungan kriteria C3.
                </small>
            </div>
            
            <div class="form-group">
                <label for="pekerjaan">Pekerjaan</label>
                <select id="pekerjaan" name="pekerjaan" class="form-control" required>
                    @php
                        $current_pekerjaan = old('pekerjaan', $jemaat->pekerjaan ?? '');
                    @endphp
                    <option value="">-- Pilih Pekerjaan --</option>
                    
                    <optgroup label="Prioritas Tinggi (Nilai 30-25)">
                        <option value="tidak bekerja" {{ $current_pekerjaan == 'tidak bekerja' ? 'selected' : '' }}>
                            Tidak Bekerja 
                        </option>
                        <option value="buruh" {{ $current_pekerjaan == 'buruh' ? 'selected' : '' }}>
                            Buruh 
                        </option>
                        <option value="petani" {{ $current_pekerjaan == 'petani' ? 'selected' : '' }}>
                            Petani 
                        </option>
                        <option value="honor lepas" {{ $current_pekerjaan == 'honor lepas' ? 'selected' : '' }}>
                            Honor Lepas 
                        </option>
                        <option value="irt menanggung sendiri" {{ $current_pekerjaan == 'irt menanggung sendiri' ? 'selected' : '' }}>
                            IRT Menanggung Sendiri 
                        </option>
                    </optgroup>
                    
                    <optgroup label="Prioritas Menengah (Nilai 20-15)">
                        <option value="umkm kecil" {{ $current_pekerjaan == 'umkm kecil' ? 'selected' : '' }}>
                            UMKM Kecil 
                        </option>
                        <option value="honorer" {{ $current_pekerjaan == 'honorer' ? 'selected' : '' }}>
                            Honorer 
                        </option>
                        <option value="wiraswasta sedang" {{ $current_pekerjaan == 'wiraswasta sedang' ? 'selected' : '' }}>
                            Wiraswasta Sedang 
                        </option>
                        <option value="swasta" {{ $current_pekerjaan == 'swasta' ? 'selected' : '' }}>
                            Swasta 
                        </option>
                    </optgroup>
                    
                    <optgroup label="Pekerjaan Stabil (Nilai 10)">
                        <option value="asn" {{ in_array($current_pekerjaan, ['asn', 'pns']) ? 'selected' : '' }}>
                            ASN / PNS 
                        </option>
                        <option value="bumn" {{ $current_pekerjaan == 'bumn' ? 'selected' : '' }}>
                            Pegawai BUMN 
                        </option>
                        <option value="tni/polri" {{ in_array($current_pekerjaan, ['tni/polri', 'tni', 'polri']) ? 'selected' : '' }}>
                            TNI / Polri 
                        </option>
                    </optgroup>
                    
                    <optgroup label="Lain-lain (Nilai 0)">
                        <option value="ditanggung orang lain" {{ $current_pekerjaan == 'ditanggung orang lain' ? 'selected' : '' }}>
                            Ditanggung Orang Lain
                        </option>
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label for="gaji_display">Gaji per Bulan (Jika Ada)</label>
                <input type="text" id="gaji_display" name="gaji_display" class="form-control" 
                       value="{{ old('gaji_display', (isset($jemaat) && $jemaat->gaji_per_bulan > 0) ? number_format($jemaat->gaji_per_bulan, 0, ',', '.') : '') }}"
                       placeholder="Contoh: 1.500.000">
                
                <input type="hidden" id="gaji_per_bulan_hidden" name="gaji_per_bulan" 
                       value="{{ old('gaji_per_bulan', $jemaat->gaji_per_bulan ?? 0) }}">
                
                <small class="form-text text-muted">
                    * Jika jemaat memiliki gaji, sistem akan menghitung berdasarkan nominal gaji. 
                    Jika diisi Rp 0, sistem akan menghitung berdasarkan jenis pekerjaan.
                </small>
            </div>

            <div class="form-group">
                <label for="status_sosial">Status Sosial</label>
                <select id="status_sosial" name="status_sosial" class="form-control" required>
                    @php
                        $current_status = old('status_sosial', $jemaat->status_sosial ?? ''); 
                    @endphp
                    <option value="">-- Pilih Status Sosial --</option>
                    
                    <optgroup label="Prioritas Tinggi (Nilai 30-25)">
                        <option value="ibu tunggal" {{ strtolower($current_status) == 'ibu tunggal' ? 'selected' : '' }}>
                            Ibu Tunggal 
                        </option>
                        <option value="ayah tunggal" {{ strtolower($current_status) == 'ayah tunggal' ? 'selected' : '' }}>
                            Ayah Tunggal 
                        </option>
                    </optgroup>
                    
                    <optgroup label="Prioritas Menengah (Nilai 20-15)">
                        <option value="lansia tinggal sendiri" {{ strtolower($current_status) == 'lansia tinggal sendiri' ? 'selected' : '' }}>
                            Lansia Tinggal Sendiri 
                        </option>
                        <option value="dewasa menanggung anggota keluarga lain" {{ strtolower($current_status) == 'dewasa menanggung anggota keluarga lain' ? 'selected' : '' }}>
                            Dewasa Menanggung Anggota Keluarga Lain 
                        </option>
                    </optgroup>
                    
                    <optgroup label="Prioritas Rendah (Nilai 10-5)">
                        <option value="menikah dengan banyak tanggungan" {{ in_array(strtolower($current_status), ['menikah dengan banyak tanggungan', 'menikah tapi punya banyak tanggungan']) ? 'selected' : '' }}>
                            Menikah dengan Banyak Tanggungan 
                        </option>
                        <option value="mahasiswa" {{ strtolower($current_status) == 'mahasiswa' ? 'selected' : '' }}>
                            Mahasiswa 
                        </option>
                    </optgroup>
                    
                    <optgroup label="Status Umum (Nilai 0)">
                        <option value="dewasa tanpa tanggungan" {{ strtolower($current_status) == 'dewasa tanpa tanggungan' ? 'selected' : '' }}>
                            Dewasa Tanpa Tanggungan 
                        </option>
                        <option value="umum" {{ strtolower($current_status) == 'umum' ? 'selected' : '' }}>
                            Umum 
                        </option>
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah_tanggungan_display">Jumlah Tanggungan</label>
                <input type="text" id="jumlah_tanggungan_display" class="form-control" 
                    value="{{ old('jumlah_tanggungan', $jemaat->jumlah_tanggungan ?? 0) }} orang" 
                    disabled
                    style="background-color: #e9ecef; cursor: not-allowed;">
                
                <input type="hidden" name="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $jemaat->jumlah_tanggungan ?? 0) }}">
                
                <small class="form-text text-muted">
                    * Jumlah tanggungan otomatis dihitung dari 
                    <a href="{{ isset($jemaat) ? route('admin.anggota.index', $jemaat) : '#' }}" 
                    style="color: #007bff; text-decoration: underline;"
                    {{ !isset($jemaat) ? 'onclick="alert(\'Simpan data jemaat terlebih dahulu\'); return false;"' : '' }}>
                        Manajemen Anggota Keluarga
                    </a>
                </small>
            </div>
        </div>
    </div>

    <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

    <button type="submit" class="btn btn-primary">{{ isset($jemaat) ? 'Update Data' : 'Simpan Jemaat' }}</button>
    <a href="{{ route('admin.jemaats.index') }}" class="btn btn-secondary">Batal</a>
    
</form>

<script>
    const gajiDisplay = document.getElementById('gaji_display');
    const gajiHidden = document.getElementById('gaji_per_bulan_hidden');

    function formatRupiah(angkaStr) {
        let angka = angkaStr.replace(/[^0-9]/g, '');
        if (angka === '' || angka === null) {
            return '';
        }
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    gajiDisplay.addEventListener('input', function(e) {
        let numericValue = e.target.value.replace(/\./g, '');
        numericValue = numericValue.replace(/[^0-9]/g, '');
        gajiHidden.value = numericValue === '' ? '0' : numericValue;
        
        if (numericValue === '0') {
            e.target.value = '0';
        } else if (numericValue) {
            e.target.value = formatRupiah(numericValue);
        } else {
            e.target.value = '';
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        if (gajiDisplay.value) {
            gajiDisplay.value = formatRupiah(gajiDisplay.value);
        }
    });
</script>
@endsection