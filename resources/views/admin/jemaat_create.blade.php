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
            
            {{-- ================================================== --}}
            {{-- === PERUBAHAN DIMULAI DI SINI (PEKERJAAN) === --}}
            {{-- ================================================== --}}
            <div class="form-group">
                <label for="pekerjaan">Pekerjaan</label>
                <select id="pekerjaan" name="pekerjaan" class="form-control" required>
                    @php
                        // Ambil nilai lama (bisa jadi 'pns', 'swasta', 'asn', dll.)
                        $current_pekerjaan = old('pekerjaan', $jemaat->pekerjaan ?? '');
                    @endphp
                    <option value="">-- Pilih Pekerjaan --</option>
                    
                    <optgroup label="Prioritas (Nilai 30-25)">
                        <option value="tidak bekerja" {{ $current_pekerjaan == 'tidak bekerja' ? 'selected' : '' }}>
                            Tidak Bekerja (Nilai 30)
                        </option>
                        <option value="buruh" {{ $current_pekerjaan == 'buruh' ? 'selected' : '' }}>
                            Buruh (Nilai 25)
                        </option>
                        <option value="petani" {{ $current_pekerjaan == 'petani' ? 'selected' : '' }}>
                            Petani (Nilai 25)
                        </option>
                        <option value="honor lepas" {{ $current_pekerjaan == 'honor lepas' ? 'selected' : '' }}>
                            Honor Lepas (Nilai 25)
                        </option>
                        <option value="irt menanggung sendiri" {{ $current_pekerjaan == 'irt menanggung sendiri' ? 'selected' : '' }}>
                            IRT (Penanggung Sendiri) (Nilai 25)
                        </option>
                    </optgroup>
                    
                    <optgroup label="Menengah (Nilai 20-15)">
                        <option value="umkm kecil" {{ $current_pekerjaan == 'umkm kecil' ? 'selected' : '' }}>
                            UMKM Kecil (Nilai 20)
                        </option>
                        <option value="swasta" {{ in_array($current_pekerjaan, ['swasta', 'wiraswasta sedang']) ? 'selected' : '' }}>
                            Swasta / Wiraswasta (Nilai 15)
                        </option>
                        <option value="honorer" {{ $current_pekerjaan == 'honorer' ? 'selected' : '' }}>
                            Honorer (Nilai 15)
                        </option>
                    </optgroup>
                    
                    <optgroup label="Stabil (Nilai 10)">
                        {{-- Ini menggabungkan 'asn' dan 'pns' ke satu pilihan --}}
                        <option value="asn" {{ in_array($current_pekerjaan, ['asn', 'pns']) ? 'selected' : '' }}>
                            ASN / PNS (Nilai 10)
                        </option>
                        <option value="bumn" {{ $current_pekerjaan == 'bumn' ? 'selected' : '' }}>
                            Pegawai BUMN (Nilai 10)
                        </option>
                         {{-- Ini menggabungkan 'tni', 'polri', dan 'tni/polri' --}}
                        <option value="tni/polri" {{ in_array($current_pekerjaan, ['tni/polri', 'tni', 'polri']) ? 'selected' : '' }}>
                            TNI / Polri (Nilai 10)
                        </option>
                    </optgroup>
                    
                    <optgroup label="Lain-lain (Nilai 0)">
                        <option value="ditanggung orang lain" {{ $current_pekerjaan == 'ditanggung orang lain' ? 'selected' : '' }}>
                            Ditanggung Orang Lain (Nilai 0)
                        </option>
                    </optgroup>
                </select>
            </div>
            {{-- ================================================== --}}
            {{-- === PERUBAHAN SELESAI === --}}
            {{-- ================================================== --}}

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
