@extends('layouts.app')
@section('title', 'Jemaat Dashboard')

@section('content')
    {{-- Kotak sambutan yang lebih modern --}}
    <div class="dashboard-welcome-card">
        <h2>Selamat Datang, {{ Auth::user()->nama }}!</h2>
        <p>Ini adalah halaman dashboard Anda. Gunakan menu di samping untuk membuat pengajuan bantuan atau melihat riwayat.</p>
    </div>

    {{-- Judul bagian pengumuman --}}
    <h3 class="section-title">Pengumuman Terbaru</h3>
    
    {{-- Kontainer untuk semua kartu pengumuman --}}
    <div class="announcement-container">
        @forelse($pengumumans as $p)
            {{-- Setiap pengumuman dibungkus dalam "card" --}}
            <div class="announcement-card">
                <h4 class="announcement-title">{{ $p->judul }}</h4>
                <small class="announcement-date">
                    Diposting pada: {{ $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('d M Y') : $p->created_at->format('d M Y') }}
                </small>
                <p class="announcement-content">
                    {!! nl2br(e($p->isi)) !!} 
                </p>
            </div>
        @empty
            <p class="announcement-empty">Belum ada pengumuman.</p>
        @endforelse
    </div>
@endsection