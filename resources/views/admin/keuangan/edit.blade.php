@extends('layouts.app')

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
            <label for="nominal">Nominal (Rp)</label>
            <input type="number" id="nominal" name="nominal" value="{{ old('nominal', $keuangan->nominal) }}" placeholder="cth: 500000" min="0" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3" required>{{ old('keterangan', $keuangan->keterangan) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Transaksi</button>
        <a href="{{ route('admin.keuangan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection