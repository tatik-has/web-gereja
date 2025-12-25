@extends('layouts.admin')

@section('title', 'Edit Transaksi')

@section('content')
    {{-- Notifikasi Error --}}
    @if($errors->any())
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>Perhatian!</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.keuangan.update', $keuangan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="tanggal">Tanggal Transaksi</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control"
                   value="{{ old('tanggal', $keuangan->tanggal) }}" required>
        </div>
        
        <div class="form-group">
            <label for="jenis">Jenis Transaksi</label>
            <select id="jenis" name="jenis" class="form-control" required onchange="toggleKategori()">
                <option value="pemasukan" {{ old('jenis', $keuangan->jenis) == 'pemasukan' ? 'selected' : '' }}>📈 Pemasukan</option>
                <option value="pengeluaran" {{ old('jenis', $keuangan->jenis) == 'pengeluaran' ? 'selected' : '' }}>📉 Pengeluaran</option>
            </select>
        </div>

        {{-- === KATEGORI (Hanya muncul jika Pemasukan) === --}}
        <div class="form-group" id="kategori-wrapper" style="display: none;">
            <label for="kategori">Kategori Pemasukan</label>
            <select id="kategori" name="kategori" class="form-control">
                <option value="">-- Pilih Kategori --</option>
                <option value="persembahan_umum" {{ old('kategori', $keuangan->kategori) == 'persembahan_umum' ? 'selected' : '' }}>
                    💰 Persembahan Umum
                </option>
                <option value="ucapan_syukur" {{ old('kategori', $keuangan->kategori) == 'ucapan_syukur' ? 'selected' : '' }}>
                    🙏 Ucapan Syukur
                </option>
                <option value="persepuluhan" {{ old('kategori', $keuangan->kategori) == 'persepuluhan' ? 'selected' : '' }}>
                    📊 Persepuluhan
                </option>
                <option value="lainnya" {{ old('kategori', $keuangan->kategori) == 'lainnya' ? 'selected' : '' }}>
                    📦 Lainnya
                </option>
            </select>
        </div>

        {{-- === PERINGATAN PENGELUARAN === --}}
        <div class="alert" id="pengeluaran-info" 
             style="display: none; background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; border-left: 5px solid #ffc107;">
            <strong>ℹ️ Informasi:</strong> Pengeluaran hanya dapat diambil dari <strong>Persembahan Umum</strong>.
        </div>

        <div class="form-group">
            <label for="nominal_display">Nominal (Rp)</label>
            <input type="text" id="nominal_display" class="form-control" placeholder="cth: 500.000" required>
            <input type="hidden" id="nominal" name="nominal" value="{{ old('nominal', $keuangan->nominal) }}">
            <small style="color: #7f8c8d; display: block; margin-top: 5px;">Format otomatis saat Anda mengetik angka</small>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3" class="form-control" required>{{ old('keterangan', $keuangan->keterangan) }}</textarea>
        </div>

        {{-- === UPLOAD BUKTI === --}}
        <div class="form-group">
            <label for="file_bukti">Upload Bukti Transaksi Baru (Opsional)</label>
            <input type="file" id="file_bukti" name="file_bukti" class="form-control" 
                   accept=".jpg,.jpeg,.png,.pdf">
            <small class="form-text text-muted">
                Format: JPG, PNG, atau PDF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.
            </small>

            {{-- Tampilkan bukti yang sudah ada --}}
            @if($keuangan->file_bukti)
                <div style="margin-top: 10px; padding: 10px; background-color: #f0f0f0; border-radius: 5px;">
                    <strong>Bukti Saat Ini:</strong>
                    <div style="margin-top: 5px;">
                        @php
                            $ext = pathinfo($keuangan->file_bukti, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                            <a href="{{ asset('storage/' . $keuangan->file_bukti) }}" target="_blank">
                                <img src="{{ asset('storage/' . $keuangan->file_bukti) }}" 
                                     alt="Bukti Transaksi" 
                                     style="max-width: 200px; max-height: 200px; border: 2px solid #ddd; border-radius: 5px;">
                            </a>
                        @else
                            <a href="{{ asset('storage/' . $keuangan->file_bukti) }}" 
                               target="_blank" 
                               style="color: #e74c3c; text-decoration: underline;">
                                📄 Lihat File PDF
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">💾 Update Transaksi</button>
        <a href="{{ route('admin.keuangan.index') }}" class="btn btn-secondary">Batal</a>
    </form>

    <script>
        // === FORMAT RUPIAH ===
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        const nominalDisplay = document.getElementById('nominal_display');
        const nominalHidden = document.getElementById('nominal');

        nominalDisplay.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            nominalHidden.value = value;
            
            if (value) {
                e.target.value = formatRupiah(value);
            } else {
                e.target.value = '';
            }
        });

        // Set nilai awal
        window.addEventListener('DOMContentLoaded', function() {
            const initialValue = '{{ old('nominal', $keuangan->nominal) }}';
            if (initialValue && initialValue !== '0') {
                nominalDisplay.value = formatRupiah(initialValue);
                nominalHidden.value = initialValue;
            }
        });

        // === TOGGLE KATEGORI & INFO ===
        function toggleKategori() {
            const jenis = document.getElementById('jenis').value;
            const kategoriWrapper = document.getElementById('kategori-wrapper');
            const kategoriSelect = document.getElementById('kategori');
            const pengeluaranInfo = document.getElementById('pengeluaran-info');

            if (jenis === 'pemasukan') {
                kategoriWrapper.style.display = 'block';
                kategoriSelect.required = true;
                pengeluaranInfo.style.display = 'none';
            } else {
                kategoriWrapper.style.display = 'none';
                kategoriSelect.required = false;
                pengeluaranInfo.style.display = 'block';
            }
        }

        window.addEventListener('DOMContentLoaded', toggleKategori);
    </script>
@endsection