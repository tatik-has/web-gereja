@extends('layouts.admin')

@section('title', 'Keuangan Gereja')

@section('content')
    <a href="{{ route('admin.keuangan.create') }}" class="btn btn-primary" style="margin-bottom: 20px;">
        <i class="fa-solid fa-plus"></i> Tambah Transaksi
    </a>

    {{-- === 3 KOTAK PEMASUKAN === --}}
    <div class="dashboard-stats" style="margin-bottom: 30px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <div class="stat-card" style="border-left: 5px solid #3498db;">
            <h3 style="color: #3498db; margin-bottom: 10px;">💰 Persembahan Umum</h3>
            <p style="font-size: 1.8rem; font-weight: bold; color: #2c3e50;">
                Rp {{ number_format($persembahan_umum, 0, ',', '.') }}
            </p>
            <small style="color: #7f8c8d;">
                Saldo setelah pengeluaran: 
                <strong style="color: {{ $saldo_persembahan_umum >= 0 ? '#27ae60' : '#e74c3c' }};">
                    Rp {{ number_format($saldo_persembahan_umum, 0, ',', '.') }}
                </strong>
            </small>
        </div>

        <div class="stat-card" style="border-left: 5px solid #9b59b6;">
            <h3 style="color: #9b59b6; margin-bottom: 10px;">🙏 Ucapan Syukur</h3>
            <p style="font-size: 1.8rem; font-weight: bold; color: #2c3e50;">
                Rp {{ number_format($ucapan_syukur, 0, ',', '.') }}
            </p>
            <small style="color: #7f8c8d;">Dana khusus ucapan syukur</small>
        </div>

        <div class="stat-card" style="border-left: 5px solid #e67e22;">
            <h3 style="color: #e67e22; margin-bottom: 10px;">📊 Persepuluhan</h3>
            <p style="font-size: 1.8rem; font-weight: bold; color: #2c3e50;">
                Rp {{ number_format($persepuluhan, 0, ',', '.') }}
            </p>
            <small style="color: #7f8c8d;">Dana persepuluhan jemaat</small>
        </div>
    </div>

    {{-- === RINGKASAN TOTAL === --}}
    <div class="dashboard-stats" style="margin-bottom: 20px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <div class="stat-card" style="border-left: 5px solid #2ecc71;">
            <h3>Total Pemasukan</h3>
            <p>Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card" style="border-left: 5px solid #e74c3c;">
            <h3>Total Pengeluaran</h3>
            <p>Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</p>
            <small style="color: #7f8c8d;">*Diambil dari Persembahan Umum</small>
        </div>
        <div class="stat-card blue">
            <h3>Saldo Akhir</h3>
            <p style="color: {{ $saldo_akhir >= 0 ? '#27ae60' : '#e74c3c' }};">
                Rp {{ number_format($saldo_akhir, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- === TABEL TRANSAKSI === --}}
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis / Kategori</th>
                <th>Keterangan</th>
                <th>Nominal</th>
                <th>Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $trx)
            <tr>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y') }}</td>
                <td>
                    @if($trx->jenis == 'pemasukan')
                        <span style="color: #2ecc71; font-weight: bold;">📈 Pemasukan</span>
                        <br>
                        <small style="color: #7f8c8d;">{{ $trx->kategori_label }}</small>
                    @else
                        <span style="color: #e74c3c; font-weight: bold;">📉 Pengeluaran</span>
                        <br>
                        <small style="color: #7f8c8d;">Dari: Persembahan Umum</small>
                    @endif
                </td>
                <td>{{ $trx->keterangan }}</td>
                <td style="font-weight: bold;">Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                <td>
                    @if($trx->file_bukti)
                        @php
                            $ext = pathinfo($trx->file_bukti, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                            <a href="{{ asset('storage/' . $trx->file_bukti) }}" target="_blank" style="color: #3498db;">
                                🖼️ Lihat Bukti
                            </a>
                        @else
                            <a href="{{ asset('storage/' . $trx->file_bukti) }}" target="_blank" style="color: #e74c3c;">
                                📄 Download PDF
                            </a>
                        @endif
                    @else
                        <span style="color: #95a5a6;">-</span>
                    @endif
                </td>
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
                <td colspan="6" style="text-align: center;">Belum ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $transaksis->links() }}
    </div>
@endsection