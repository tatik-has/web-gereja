@extends('layouts.admin')

@section('title', 'Detail Pengajuan Bantuan - Akses Pendeta')

<link rel="stylesheet" href="{{ asset('css/pendeta.css') }}">

@section('content')
<div class="content-wrapper">

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert-success">
            <span class="icon-check"></span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Detail Container -->
    <div class="table-container" style="padding: 2rem;">
        <!-- Header Card -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 8px 8px 0 0; margin: -2rem -2rem 2rem -2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="color: white; font-size: 1.3rem; font-weight: 700; margin: 0;">📋 Informasi Pengajuan</h3>
                <span class="status-badge 
                    @if($pengajuan->status == 'pending') badge-warning
                    @elseif($pengajuan->status == 'disetujui' || $pengajuan->status == 'diterima') badge-success
                    @else badge-danger
                    @endif">
                    @if($pengajuan->status == 'pending')
                         Pending
                    @elseif($pengajuan->status == 'disetujui' || $pengajuan->status == 'diterima')
                         Diterima
                    @else
                         Ditolak
                    @endif
                </span>
            </div>
        </div>

        <!-- Data Pengaju Section -->
        <div style="margin-bottom: 2rem;">
            <h4 style="color: #2c3e50; font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
                 Data Pengaju
            </h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Nama Lengkap</p>
                    <p style="color: #1f2937; font-size: 1rem; font-weight: 500; margin: 0;">{{ $pengajuan->jemaat->nama ?? '-' }}</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Email</p>
                    <p style="color: #1f2937; font-size: 1rem; font-weight: 500; margin: 0;">{{ $pengajuan->jemaat->email ?? '-' }}</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">No. Telepon</p>
                    <p style="color: #1f2937; font-size: 1rem; font-weight: 500; margin: 0;">{{ $pengajuan->jemaat->no_telepon ?? '-' }}</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Alamat</p>
                    <p style="color: #1f2937; font-size: 1rem; font-weight: 500; margin: 0;">{{ $pengajuan->jemaat->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>

        <hr style="border: none; border-top: 2px solid #f0f2f5; margin: 2rem 0;">

        <!-- Detail Pengajuan Section -->
        <div style="margin-bottom: 2rem;">
            <h4 style="color: #2c3e50; font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
                 Detail Pengajuan
            </h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Judul Pengajuan</p>
                    <p style="color: #1f2937; font-size: 1rem; font-weight: 500; margin: 0;">{{ $pengajuan->judul ?? '-' }}</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Jenis Musibah</p>
                    <p style="color: #1f2937; font-size: 1rem; font-weight: 500; margin: 0;">{{ $pengajuan->musibah->nama_musibah ?? 'Tidak Ada Musibah' }}</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Tanggal Pengajuan</p>
                    <p style="color: #1f2937; font-size: 1rem; font-weight: 500; margin: 0;">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d F Y, H:i') }} WIB</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Status</p>
                    <p style="margin: 0;">
                        <span class="status-badge 
                            @if($pengajuan->status == 'pending') badge-warning
                            @elseif($pengajuan->status == 'disetujui' || $pengajuan->status == 'diterima') badge-success
                            @else badge-danger
                            @endif">
                            {{ ucfirst($pengajuan->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div>
                <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase;">Keterangan / Alasan</p>
                <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; border-left: 4px solid #667eea;">
                    <p style="color: #374151; font-size: 1rem; line-height: 1.6; margin: 0;">{{ $pengajuan->keterangan ?? 'Tidak ada keterangan.' }}</p>
                </div>
            </div>
        </div>

        <!-- File Bukti -->
        @if($pengajuan->file_bukti)
        <hr style="border: none; border-top: 2px solid #f0f2f5; margin: 2rem 0;">
        <div style="margin-bottom: 2rem;">
            <h4 style="color: #2c3e50; font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
                 File Bukti Pendukung
            </h4>
            <a href="{{ asset('storage/' . $pengajuan->file_bukti) }}" target="_blank" class="btn-detail" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
                <span class="btn-icon">📥</span>
                Lihat / Download File Bukti
            </a>
        </div>
        @endif

        <!-- Perhitungan SMART -->
        @if($pengajuan->perhitunganSmart)
        <hr style="border: none; border-top: 2px solid #f0f2f5; margin: 2rem 0;">
        <div style="margin-bottom: 2rem;">
            <h4 style="color: #2c3e50; font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
                 Hasil Perhitungan SMART
            </h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Skor Akhir</p>
                    <p style="color: #667eea; font-size: 1.5rem; font-weight: 700; margin: 0;">{{ number_format($pengajuan->perhitunganSmart->skor_akhir ?? 0, 4) }}</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; text-transform: uppercase;">Ranking</p>
                    <p style="color: #1f2937; font-size: 1.3rem; font-weight: 600; margin: 0;">{{ $pengajuan->perhitunganSmart->ranking ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Button -->
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #f0f2f5;">
            <a href="{{ route('pendeta.pengajuan.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; background: #6b7280; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                <span style="font-size: 1.2rem;">←</span>
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection