@extends('layouts.admin')

@section('title', 'Laporan Keuangan Gereja')

@section('content')
    <div style="margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin: 0 0 10px 0;">
            <i class="fa-solid fa-wallet"></i> Laporan Keuangan Gereja
        </h2>
        <p style="color: #7f8c8d; margin: 0;">Ringkasan pergerakan dana gereja (Akses Read-Only)</p>
    </div>

    {{-- === 3 KOTAK KATEGORI PEMASUKAN === --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid #3498db;">
            <h3 style="color: #3498db; margin: 0 0 15px 0; font-size: 1.1rem;">
                <i class="fa-solid fa-hand-holding-heart"></i> Persembahan Umum
            </h3>
            <p style="font-size: 1.8rem; font-weight: bold; color: #2c3e50; margin: 10px 0;">
                Rp {{ number_format($persembahan_umum, 0, ',', '.') }}
            </p>
            <small style="color: #7f8c8d;">
                Saldo setelah pengeluaran: 
                <strong style="color: {{ $saldo_persembahan_umum >= 0 ? '#27ae60' : '#e74c3c' }};">
                    Rp {{ number_format($saldo_persembahan_umum, 0, ',', '.') }}
                </strong>
            </small>
        </div>

        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid #9b59b6;">
            <h3 style="color: #9b59b6; margin: 0 0 15px 0; font-size: 1.1rem;">
                <i class="fa-solid fa-praying-hands"></i> Ucapan Syukur
            </h3>
            <p style="font-size: 1.8rem; font-weight: bold; color: #2c3e50; margin: 10px 0;">
                Rp {{ number_format($ucapan_syukur, 0, ',', '.') }}
            </p>
            <small style="color: #7f8c8d;">Dana khusus ucapan syukur jemaat</small>
        </div>

        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid #e67e22;">
            <h3 style="color: #e67e22; margin: 0 0 15px 0; font-size: 1.1rem;">
                <i class="fa-solid fa-percentage"></i> Persepuluhan
            </h3>
            <p style="font-size: 1.8rem; font-weight: bold; color: #2c3e50; margin: 10px 0;">
                Rp {{ number_format($persepuluhan, 0, ',', '.') }}
            </p>
            <small style="color: #7f8c8d;">Dana persepuluhan (10%) dari jemaat</small>
        </div>
    </div>

    {{-- === RINGKASAN TOTAL === --}}
    <div class="dashboard-stats" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background-color: #d4edda; color: #155724; padding: 20px; border-radius: 8px;">
            <h3 style="font-size: 1rem; margin: 0 0 10px 0;">Total Pemasukan</h3>
            <p style="font-size: 1.8rem; font-weight: bold; margin: 0;">
                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
            </p>
            <small style="opacity: 0.8;">Jumlah seluruh pemasukan</small>
        </div>

        <div class="stat-card" style="background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 8px;">
            <h3 style="font-size: 1rem; margin: 0 0 10px 0;">Total Pengeluaran</h3>
            <p style="font-size: 1.8rem; font-weight: bold; margin: 0;">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </p>
            <small style="opacity: 0.8;">*Diambil dari Persembahan Umum</small>
        </div>

        <div class="stat-card" style="background-color: #cce5ff; color: #004085; padding: 20px; border-radius: 8px;">
            <h3 style="font-size: 1rem; margin: 0 0 10px 0;">Saldo Akhir</h3>
            <p style="font-size: 1.8rem; font-weight: bold; margin: 0;">
                Rp {{ number_format($saldo_akhir, 0, ',', '.') }}
            </p>
            <small style="opacity: 0.8;">Total saldo keseluruhan</small>
        </div>
    </div>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #dee2e6;">

    {{-- === TABEL DETAIL TRANSAKSI === --}}
    <h3 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fa-solid fa-list"></i> Detail Transaksi
    </h3>

    <table class="table table-bordered" style="background: white;">
        <thead style="background: #f8f9fa;">
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 120px;">Tanggal</th>
                <th style="width: 150px;">Jenis / Kategori</th>
                <th>Keterangan</th>
                <th style="width: 150px; text-align: right;">Nominal</th>
                <th style="width: 100px; text-align: center;">Bukti</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keuangans as $index => $keuangan)
                <tr>
                    <td style="text-align: center;">{{ $keuangans->firstItem() + $index }}</td>
                    <td>{{ \Carbon\Carbon::parse($keuangan->tanggal)->format('d M Y') }}</td>
                    <td>
                        @if($keuangan->jenis == 'pemasukan')
                            <span style="background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                📈 Pemasukan
                            </span>
                            <br>
                            <small style="color: #7f8c8d;">{{ $keuangan->kategori_label }}</small>
                        @else
                            <span style="background: #f8d7da; color: #721c24; padding: 4px 10px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                📉 Pengeluaran
                            </span>
                            <br>
                            <small style="color: #7f8c8d;">Dari: Persembahan Umum</small>
                        @endif
                    </td>
                    <td>{{ $keuangan->keterangan }}</td>
                    <td style="text-align: right; font-weight: 600;">
                        Rp {{ number_format($keuangan->nominal, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        @if($keuangan->file_bukti)
                            @php
                                $ext = pathinfo($keuangan->file_bukti, PATHINFO_EXTENSION);
                            @endphp
                            
                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                <a href="{{ asset('storage/' . $keuangan->file_bukti) }}" 
                                   target="_blank" 
                                   style="color: #3498db; text-decoration: none;">
                                    <i class="fa-solid fa-image"></i> Lihat
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $keuangan->file_bukti) }}" 
                                   target="_blank" 
                                   style="color: #e74c3c; text-decoration: none;">
                                    <i class="fa-solid fa-file-pdf"></i> PDF
                                </a>
                            @endif
                        @else
                            <span style="color: #95a5a6;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #7f8c8d;">
                        <i class="fa-solid fa-inbox" style="font-size: 2rem;"></i>
                        <br><br>
                        Belum ada data transaksi keuangan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $keuangans->links() }}
    </div>

    {{-- Info Read-Only --}}
    <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-left: 5px solid #ffc107; border-radius: 5px;">
        <i class="fa-solid fa-info-circle" style="color: #856404;"></i> 
        <strong style="color: #856404;">Catatan:</strong> 
        <span style="color: #856404;">
            Anda sedang melihat laporan keuangan dalam mode <strong>Read-Only</strong>. 
            Untuk menambah/mengubah/menghapus transaksi, hubungi Admin.
        </span>
    </div>
@endsection