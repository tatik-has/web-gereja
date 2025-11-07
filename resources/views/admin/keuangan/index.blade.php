@extends('layouts.app')

@section('title', 'Keuangan Gereja')

@section('content')
    <a href="{{ route('admin.keuangan.create') }}" class="btn btn-primary" style="margin-bottom: 20px;">
        <i class="fa-solid fa-plus"></i> Tambah Transaksi
    </a>

    <div class="dashboard-stats" style="margin-bottom: 20px;">
        <div class="stat-card" style="border-left-color: #2ecc71;"> <h3>Total Pemasukan</h3>
            <p>Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card" style="border-left-color: #e74c3c;"> <h3>Total Pengeluaran</h3>
            <p>Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card blue"> <h3>Saldo Akhir</h3>
            <p>Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $trx)
            <tr>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y') }}</td>
                <td>{{ $trx->keterangan }}</td>
                <td>
                    @if($trx->jenis == 'pemasukan')
                        <span style="color: #2ecc71; font-weight: bold;">Pemasukan</span>
                    @else
                        <span style="color: #e74c3c; font-weight: bold;">Pengeluaran</span>
                    @endif
                </td>
                <td>Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('admin.keuangan.edit', $trx->id) }}" class="btn btn-secondary" style="padding: 5px 10px;">Edit</a>
                    <form action="{{ route('admin.keuangan.destroy', $trx->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background-color: #e74c3c; color: white; padding: 5px 10px;">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $transaksis->links() }}
    </div>
@endsection