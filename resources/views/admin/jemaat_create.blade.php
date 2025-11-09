@extends('layouts.admin')

@section('title', isset($jemaat) ? 'Edit Data Jemaat' : 'Tambah Jemaat Baru')

@section('content')
    
    <form action="{{ isset($jemaat) ? route('admin.jemaats.update', $jemaat->id) : route('admin.jemaats.store') }}" method="POST">
        @csrf
        
        @if(isset($jemaat))
            @method('PUT')
        @endif
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            
            <div>
                <div class="form-group">
                    <label for="nama">Nama Lengkap Jemaat</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $jemaat->nama ?? '') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email (Untuk Login)</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $jemaat->email ?? '') }}">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                           placeholder="{{ isset($jemaat) ? 'Kosongkan jika tidak diubah' : 'Wajib diisi' }}">
                    
                    @if(!isset($jemaat))
                        <small style="color: #555;">Password wajib diisi untuk jemaat baru.</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="no_hp">No. HP / WhatsApp</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $jemaat->no_hp ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3">{{ old('alamat', $jemaat->alamat ?? '') }}</textarea>
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

            <div>
                <div class="form-group">
                    <label for="pekerjaan">Pekerjaan</label>
                    <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $jemaat->pekerjaan ?? '') }}" placeholder="cth: Buruh, IRT, Tidak Bekerja">
                </div>

                @php
                    // Ambil nilai mentah (raw) dari old() atau dari database
                    $gaji_raw = old('gaji_per_bulan', $jemaat->gaji_per_bulan ?? 0);
                    
                    // Ubah tipe data decimal dari DB (misal 1500000.00) ke integer
                    $gaji_raw_int = intval($gaji_raw); 
                    
                    // Format nilai ini untuk ditampilkan
                    $gaji_formatted = number_format($gaji_raw_int, 0, ',', '.');
                @endphp

                <div class="form-group">
                    <label for="gaji_display">Gaji per Bulan (Rp)</label>
                    
                    <input type="text" id="gaji_display" 
                           value="{{ $gaji_formatted }}" 
                           placeholder="cth: 1.500.000" class="form-control"
                           inputmode="numeric"> <input type="hidden" id="gaji_per_bulan_hidden" name="gaji_per_bulan" 
                           value="{{ $gaji_raw_int }}">
                </div>
                
                <div class="form-group">
                    <label for="usia">Usia (Tahun)</label>
                    <input type="number" id="usia" name="usia" value="{{ old('usia', $jemaat->usia ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="status_sosial">Status Sosial</label>
                    <select id="status_sosial" name="status_sosial" class="form-control">
                        @php
                            $current_status = old('status_sosial', $jemaat->status_sosial ?? 'Umum'); 
                        @endphp
                        <option value="Umum" {{ $current_status == 'Umum' ? 'selected' : '' }}>Umum</option>
                        <option value="Ibu Tunggal" {{ $current_status == 'Ibu Tunggal' ? 'selected' : '' }}>Ibu Tunggal</option>
                        <option value="Ayah Tunggal" {{ $current_status == 'Ayah Tunggal' ? 'selected' : '' }}>Ayah Tunggal</option>
                        <option value="Lansia" {{ $current_status == 'Lansia' ? 'selected' : '' }}>Lansia</option>
                        <option value="Yatim Piatu" {{ $current_status == 'Yatim Piatu' ? 'selected' : '' }}>Yatim Piatu</option>
                        <option value="Mahasiswa" {{ $current_status == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="Keluarga Tidak Mampu" {{ $current_status == 'Keluarga Tidak Mampu' ? 'selected' : '' }}>Keluarga Tidak Mampu</option>
                    </select>
                </div>
                
            </div>
        </div>

        <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

        <button type="submit" class="btn btn-primary">{{ isset($jemaat) ? 'Update Data' : 'Simpan Jemaat' }}</button>
        <a href="{{ route('admin.jemaats.index') }}" class="btn btn-secondary">Batal</a>
        
    </form>

    <script>
        // Ambil elemen input
        const gajiDisplay = document.getElementById('gaji_display');
        const gajiHidden = document.getElementById('gaji_per_bulan_hidden');

        // Fungsi untuk memformat angka ke string Rupiah (1500000 -> "1.500.000")
        function formatRupiah(angkaStr) {
            // 1. Hapus semua karakter non-digit
            let angka = angkaStr.replace(/[^0-9]/g, '');
            
            // 2. Jika kosong, kembalikan string kosong
            if (angka === '' || angka === null) {
                return '';
            }
            
            // 3. Format dengan titik menggunakan Intl.NumberFormat
            // Ini adalah cara modern untuk format angka sesuai lokal (id-ID = Indonesia)
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Tambahkan event listener 'input' (berjalan setiap kali ada ketikan)
        gajiDisplay.addEventListener('input', function(e) {
            
            // 1. Ambil nilai yang diketik, dan hapus semua titik (misal "1.500.000" -> "1500000")
            let numericValue = e.target.value.replace(/\./g, '');
            
            // 2. Hapus semua karakter non-digit (jaga-jaga jika user paste aneh)
            numericValue = numericValue.replace(/[^0-9]/g, '');

            // 3. Update nilai input tersembunyi (hidden) dengan angka murni
            gajiHidden.value = numericValue;
            
            // 4. Format ulang nilai yang terlihat di display
            if (numericValue === '0') {
                e.target.value = '0';
            } else if (numericValue) {
                e.target.value = formatRupiah(numericValue);
            } else {
                e.target.value = ''; // Kosongkan jika dihapus semua
            }
        });
    </script>
@endsection