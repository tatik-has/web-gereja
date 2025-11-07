@extends('layouts.app')

@section('title', 'Detail Pengajuan: ' . $pengajuan->judul)

@section('content')

<a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary" style="margin-bottom: 20px;">
    &larr; Kembali ke Daftar Pengajuan
</a>

{{-- Notifikasi Sukses --}}
@if(session('success'))
    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    
    <div>
        <h3>Informasi Pengajuan</h3>
        <table class="table-detail">
            <tr>
                <th>Judul</th>
                <td>{{ $pengajuan->judul }}</td>
            </tr>
            <tr>
                <th>Jenis Musibah</th>
                <td>{{ $pengajuan->musibah->nama ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $pengajuan->keterangan ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Diajukan</th>
                <td>{{ $pengajuan->created_at->format('d M Y, H:i') }}</td>
            </tr>
            
            {{-- === BAGIAN YANG DIPERBARUI === --}}
            <tr>
                <th>Status Saat Ini</th>
                <td>
                    @if($pengajuan->status == 'pending')
                        <span style="color: #f39c12; font-weight: bold;">Pending</span>
                    @elseif($pengajuan->status == 'diterima')
                        <span style="color: #2ecc71; font-weight: bold;">Diterima</span>
                    @else
                        <span style="color: #e74c3c; font-weight: bold;">Ditolak</span>
                    @endif
                </td>
            </tr>
            
            {{-- Tambahkan Form Aksi jika status masih 'pending' --}}
            @if($pengajuan->status == 'pending')
            <tr>
                <th>Aksi Admin</th>
                <td>
                    <div style="display: flex; gap: 10px;">
                        {{-- Form untuk 'TERIMA' --}}
                        <form action="{{ route('admin.pengajuan.updateStatus', $pengajuan->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="diterima">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Anda yakin ingin MENERIMA pengajuan ini?')">
                                <i class="fa-solid fa-check"></i> Terima
                            </button>
                        </form>
                        
                        {{-- Form untuk 'TOLAK' --}}
                        <form action="{{ route('admin.pengajuan.updateStatus', $pengajuan->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="ditolak">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Anda yakin ingin MENOLAK pengajuan ini?')">
                                <i class="fa-solid fa-times"></i> Tolak
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endif
            {{-- === AKHIR BAGIAN YANG DIPERBARUI === --}}
            
        </table>

        <h3 style="margin-top: 30px;">Data Jemaat Pemohon</h3>
        <table class="table-detail">
            <tr>
                <th>Nama</th>
                <td>{{ $pengajuan->jemaat->nama ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $pengajuan->jemaat->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <th>Pekerjaan</th>
                <td>{{ $pengajuan->jemaat->pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <th>Gaji/Bulan</th>
                <td>Rp {{ number_format($pengajuan->jemaat->gaji_per_bulan ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status Sosial</th>
                <td>{{ $pengajuan->jemaat->status_sosial ?? '-' }}</td>
            </tr>
            <tr>
                <th>Usia</th>
                <td>{{ $pengajuan->jemaat->usia ?? '-' }} Tahun</td>
            </tr>
            <tr>
                <th>Tanggungan</th>
                <td>{{ $pengajuan->jemaat->tanggungan ?? '-' }} Orang</td>
            </tr>
        </table>
    </div>

    <div>
        <h3>Hasil Perhitungan SMART</h3>

        @if($pengajuan->perhitunganSmart)
            @php
                $hasil = $pengajuan->perhitunganSmart;
                $kriteria = $hasil->nilai_per_kriteria ?? [];
            @endphp
            <table class="table-detail">
                <tr style="background-color: #f1f8ff;">
                    <th style="font-size: 1.2rem;">SKOR AKHIR</th>
                    <td style="font-size: 1.2rem; font-weight: bold; color: #004a99;">
                        {{ $hasil->total_score }}
                    </td>
                </tr>
                <tr>
                    <th>Nilai C1 (Kondisi Darurat)</th>
                    <td>{{ $kriteria['C1'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Nilai C2 (Penghasilan)</th>
                    <td>{{ $kriteria['C2'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Nilai C3 (Usia)</th>
                    <td>{{ $kriteria['C3'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Nilai C4 (Status Sosial)</th>
                    <td>{{ $kriteria['C4'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Nilai C5 (Tanggungan)</th>
                    <td>{{ $kriteria['C5'] ?? 'N/A' }}</td>
                </tr>
            </table>
            <small>Nilai kriteria di atas adalah nilai utilitas (Uij) setelah normalisasi (skala 0-100).</small>

            @php
                $score = $hasil->total_score;
                $rekomendasi = '';
                $alasan = '';
                $style = '';

                if ($score > 0.75) {
                    // Sangat Direkomendasikan (Hijau)
                    $rekomendasi = 'Sangat Direkomendasikan untuk Diterima';
                    $alasan = 'Skor akhir sangat tinggi (di atas 0.75), ini menunjukkan tingkat urgensi dan kelayakan pemohon sangat tinggi.';
                    $style = 'background-color: #d4edda; color: #155724;'; // Style Sukses (Hijau)
                
                } elseif ($score > 0.50) {
                    // Direkomendasikan (Biru)
                    $rekomendasi = 'Direkomendasikan untuk Diterima';
                    $alasan = 'Skor akhir cukup (di atas 0.50). Pengajuan ini layak diterima, namun prioritasnya masih di bawah pengajuan dengan skor > 0.75.';
                    $style = 'background-color: #d1ecf1; color: #0c5460;'; // Style Info (Biru)
                
                } else {
                    // Perlu Pertimbangan (Kuning)
                    $rekomendasi = 'Perlu Pertimbangan Kembali';
                    $alasan = 'Skor akhir rendah (di bawah 0.50). Disarankan untuk meninjau kembali data pemohon atau memprioritaskan pengajuan lain yang lebih mendesak.';
                    $style = 'background-color: #fff3cd; color: #856404;'; // Style Warning (Kuning)
                }
            @endphp

            <div style="padding: 15px; border-radius: 5px; margin-top: 20px; {{ $style }}">
                <h4 style="margin-top: 0; margin-bottom: 10px; font-weight: bold;">Rekomendasi Keputusan</h4>
                <strong style="font-size: 1.1rem;">{{ $rekomendasi }}</strong>
                <p style="margin-top: 8px; margin-bottom: 0;">{{ $alasan }}</p>
            </div>

        @else
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;">
                Perhitungan SMART belum dilakukan untuk pengajuan ini.
            </div>
            
            <p>Silakan pergi ke menu <strong>"Perhitungan SMART"</strong> untuk memproses pengajuan ini.</p>
            
            <form method="post" action="{{ route('admin.perhitungan.hitung') }}">
                @csrf
                <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-calculator"></i> Hitung SMART Sekarang
                </button>
            </form>
        @endif
    </div>
</div>

<style>
    .table-detail { width: 100%; border-collapse: collapse; }
    .table-detail th, .table-detail td {
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
        text-align: left;
        vertical-align: top;
    }
    .table-detail th {
        background-color: #f8f9fa;
        font-weight: 600;
        width: 40%; /* Lebar kolom header */
    }
    .table-detail tr:hover { background-color: #fcfcfc; }
    
    /* Style untuk tombol */
    .btn {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
    }
    .btn-success { background-color: #28a745; color: white; }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-primary { background-color: #007bff; color: white; }
</style>


@endsection