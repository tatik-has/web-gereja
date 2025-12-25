@extends('layouts.admin')

@section('title', 'Tambah Transaksi Baru')

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

    <form action="{{ route('admin.keuangan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="tanggal">Tanggal Transaksi</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" 
                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
        </div>
        
        <div class="form-group">
            <label for="jenis">Jenis Transaksi</label>
            <select id="jenis" name="jenis" class="form-control" required onchange="toggleKategori()">
                <option value="pemasukan" {{ old('jenis') == 'pemasukan' ? 'selected' : '' }}>📈 Pemasukan</option>
                <option value="pengeluaran" {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }}>📉 Pengeluaran</option>
            </select>
        </div>

        {{-- === KATEGORI (Hanya muncul jika Pemasukan) === --}}
        <div class="form-group" id="kategori-wrapper" style="display: none;">
            <label for="kategori">Kategori Pemasukan</label>
            <select id="kategori" name="kategori" class="form-control">
                <option value="">-- Pilih Kategori --</option>
                <option value="persembahan_umum" {{ old('kategori') == 'persembahan_umum' ? 'selected' : '' }}>
                    💰 Persembahan Umum
                </option>
                <option value="ucapan_syukur" {{ old('kategori') == 'ucapan_syukur' ? 'selected' : '' }}>
                    🙏 Ucapan Syukur
                </option>
                <option value="persepuluhan" {{ old('kategori') == 'persepuluhan' ? 'selected' : '' }}>
                    📊 Persepuluhan
                </option>
                
            </select>
            <small style="color: #7f8c8d; display: block; margin-top: 5px;">
                Pilih sumber pemasukan
            </small>
        </div>

        {{-- === PERINGATAN PENGELUARAN === --}}
        <div class="alert" id="pengeluaran-info" 
             style="display: none; background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; border-left: 5px solid #ffc107;">
            <strong>ℹ️ Informasi:</strong> Pengeluaran hanya dapat diambil dari <strong>Persembahan Umum</strong>.
        </div>

        <div class="form-group">
            <label for="nominal_display">Nominal (Rp)</label>
            <input type="text" id="nominal_display" class="form-control" placeholder="cth: 500.000" required>
            <input type="hidden" id="nominal" name="nominal" value="{{ old('nominal', 0) }}">
            <small style="color: #7f8c8d; display: block; margin-top: 5px;">Format otomatis saat Anda mengetik angka</small>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3" class="form-control" required>{{ old('keterangan') }}</textarea>
        </div>

        {{-- === UPLOAD BUKTI === --}}
        <div class="form-group">
            <label for="file_bukti">Upload Bukti Transaksi (Opsional)</label>
            <input type="file" id="file_bukti" name="file_bukti" class="form-control" 
                   accept=".jpg,.jpeg,.png,.pdf">
            <small class="form-text text-muted">
                Format: JPG, PNG, atau PDF. Maksimal 2MB.
            </small>
        </div>

        <button type="submit" class="btn btn-primary">💾 Simpan Transaksi</button>
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

        @if(old('nominal'))
            nominalDisplay.value = formatRupiah('{{ old('nominal') }}');
            nominalHidden.value = '{{ old('nominal') }}';
        @endif

        // === TOGGLE KATEGORI & INFO BERDASARKAN JENIS ===
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

        // Jalankan saat halaman dimuat
        window.addEventListener('DOMContentLoaded', toggleKategori);
    </script>
@endsection