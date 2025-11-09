@extends('layouts.admin')

@section('title', 'Edit Transaksi')

@section('content')
    <form action="{{ route('admin.keuangan.update', $keuangan->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="tanggal">Tanggal Transaksi</label>
            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $keuangan->tanggal) }}" required>
        </div>
        
        <div class="form-group">
            <label for="jenis">Jenis Transaksi</label>
            <select id="jenis" name="jenis" required>
                <option value="pemasukan" {{ old('jenis', $keuangan->jenis) == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ old('jenis', $keuangan->jenis) == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>

        <div class="form-group">
            <label for="nominal_display">Nominal (Rp)</label>
            <input type="text" id="nominal_display" placeholder="cth: 500.000" required>
            <input type="hidden" id="nominal" name="nominal" value="{{ old('nominal', $keuangan->nominal) }}">
            <small style="color: #7f8c8d; display: block; margin-top: 5px;">Format otomatis saat Anda mengetik angka</small>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3" required>{{ old('keterangan', $keuangan->keterangan) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Transaksi</button>
        <a href="{{ route('admin.keuangan.index') }}" class="btn btn-secondary">Batal</a>
    </form>

    <script>
        // Format angka ke format rupiah (70000 -> 70.000)
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Hapus format rupiah (70.000 -> 70000)
        function unformatRupiah(rupiah) {
            return rupiah.replace(/\./g, '');
        }

        const nominalDisplay = document.getElementById('nominal_display');
        const nominalHidden = document.getElementById('nominal');

        // Event saat user mengetik
        nominalDisplay.addEventListener('input', function(e) {
            // Ambil value dan hapus semua karakter non-digit
            let value = e.target.value.replace(/\D/g, '');
            
            // Update hidden input dengan angka murni
            nominalHidden.value = value;
            
            // Update display dengan format rupiah
            if (value) {
                e.target.value = formatRupiah(value);
            } else {
                e.target.value = '';
            }
        });

        // Set nilai awal dari database
        window.addEventListener('DOMContentLoaded', function() {
            const initialValue = '{{ old('nominal', $keuangan->nominal) }}';
            if (initialValue && initialValue !== '0') {
                nominalDisplay.value = formatRupiah(initialValue);
                nominalHidden.value = initialValue;
            }
        });
    </script>
@endsection