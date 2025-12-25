@extends('layouts.admin')

@section('title', 'Laporan Keuangan Gereja - Akses Pendeta')

@section('content')
    <div style="margin-bottom: 20px;">
        <p>Ringkasan pergerakan dana gereja.</p>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="dashboard-stats" style="display: flex; gap: 20px; margin-bottom: 30px;">

        <div class="stat-card"
            style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; flex: 1;">
            <h3>Total Pemasukan</h3>
            {{-- Variabel dikirim dari Pendeta\KeuanganController@index --}}
            <p style="font-size: 1.5rem; font-weight: bold;">Rp. {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</p>
        </div>

        <div class="stat-card"
            style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; flex: 1;">
            <h3>Total Pengeluaran</h3>
            <p style="font-size: 1.5rem; font-weight: bold;">Rp. {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
            </p>
        </div>

        <div class="stat-card"
            style="background-color: #cce5ff; color: #004085; padding: 15px; border-radius: 5px; flex: 1;">
            <h3>Saldo Bersih</h3>
            <p style="font-size: 1.5rem; font-weight: bold;">Rp.
                {{ number_format(($totalPemasukan - $totalPengeluaran) ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <hr>

    <h3>Detail Transaksi</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keuangans as $index => $keuangan)
                <tr>
                    <td>{{ $keuangans->firstItem() + $index }}</td>
                    <td>{{ \Carbon\Carbon::parse($keuangan->tanggal)->format('d M Y') }}</td>
                    <td>
                        <span class="badge 
                                    @if($keuangan->jenis == 'pemasukan') badge-success
                                    @else badge-danger
                                    @endif">
                            {{ ucfirst($keuangan->jenis) }}
                        </span>
                    </td>
                    <td>{{ $keuangan->keterangan }}</td>
                    <td style="text-align: right;">Rp. {{ number_format($keuangan->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data transaksi keuangan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $keuangans->links() }}
@endsection