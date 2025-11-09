@extends('layouts.jemaat')

@section('title', 'Jemaat Dashboard')

@section('content')

{{-- Welcome Card --}}
<div class="welcome-card">
    <div class="welcome-icon">
        <i class="fa-solid fa-hands-praying"></i>
    </div>
    <div class="welcome-text">
        <h1>Selamat Datang, {{ $jemaat->nama }}!</h1>
        <p>Ini adalah halaman dashboard Anda. Gunakan menu di samping untuk membuat pengajuan bantuan atau melihat riwayat.</p>
    </div>
</div>

{{-- Section Pengumuman dari Database --}}
<div class="announcement-section">
    <div class="section-header">
        <h2><i class="fa-solid fa-bullhorn"></i> Pengumuman Terbaru</h2>
    </div>

    @forelse($pengumumans as $index => $pengumuman)
        <div class="announcement-card">
            @if($index === 0)
                <div class="announcement-badge">TERBARU</div>
            @endif
            <h3>{{ $pengumuman->judul }}</h3>
            <div class="announcement-meta">
                <span>
                    <i class="fa-solid fa-calendar"></i> 
                    Diposting pada {{ \Carbon\Carbon::parse($pengumuman->created_at)->format('d M Y') }}
                </span>
            </div>
            <div class="announcement-content">
                <p>{{ $pengumuman->isi }}</p>
            </div>
        </div>
    @empty
        <div class="announcement-card empty-state">
            <div class="empty-icon">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <h3>Belum Ada Pengumuman</h3>
            <p>Saat ini belum ada pengumuman yang tersedia. Silakan cek kembali nanti.</p>
        </div>
    @endforelse
</div>

@endsection