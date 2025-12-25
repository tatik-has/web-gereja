@extends('layouts.admin')

@section('title', 'Dashboard Pendeta')

<link rel="stylesheet" href="{{ asset('css/pendeta-dashboard.css') }}">

@section('content')
<!-- GANTI SEMUA ISI FILE resources/views/pendeta/dashboard.blade.php DENGAN KODE INI -->
<div class="dashboard-container">
    {{-- Header --}}
    <div class="dashboard-header">
        <h1><i class="fas fa-chart-line"></i> Dashboard Pendeta</h1>
        <p>Selamat datang di panel informasi Gereja HKBP Bengkalis (Read-Only)</p>
    </div>

    {{-- Info Read-Only --}}
    <div class="info-readonly">
        <i class="fas fa-info-circle"></i>
        <div class="info-text">
            <strong>Mode Read-Only:</strong> 
            Anda dapat melihat semua informasi namun tidak dapat melakukan perubahan data. 
            Untuk melakukan perubahan, hubungi Admin.
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        {{-- Total Jemaat --}}
        <div class="stat-card blue">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Total Jemaat</div>
                </div>
                <div class="stat-card-icon blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $totalJemaat }}</div>
            <div class="stat-card-subtitle">
                {{ $jemaatAktif }} aktif, {{ $jemaatPending }} pending
            </div>
        </div>

        {{-- Pengajuan Baru --}}
        <div class="stat-card orange">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Pengajuan Baru</div>
                </div>
                <div class="stat-card-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $pengajuanBaru }}</div>
            <div class="stat-card-subtitle">Menunggu persetujuan</div>
        </div>

        {{-- Total Pengajuan --}}
        <div class="stat-card purple">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Total Pengajuan</div>
                </div>
                <div class="stat-card-icon purple">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $totalPengajuan }}</div>
            <div class="stat-card-subtitle">
                {{ $pengajuanDiterima }} diterima, {{ $pengajuanDitolak }} ditolak
            </div>
        </div>

        {{-- Saldo Kas --}}
        <div class="stat-card green">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Saldo Kas</div>
                </div>
                <div class="stat-card-icon green">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="stat-card-value" style="font-size: 1.8rem;">
                Rp {{ number_format($saldoKas, 0, ',', '.') }}
            </div>
            <div class="stat-card-subtitle">
                Pemasukan: Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="content-grid">
        {{-- Pengajuan Terbaru --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-file-alt"></i>
                    Pengajuan Terbaru
                </div>
                <a href="{{ route('pendeta.pengajuan.index') }}" class="btn-link">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if($pengajuanTerbaru->count() > 0)
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama Jemaat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuanTerbaru as $pengajuan)
                                <tr>
                                    <td>{{ $pengajuan->jemaat->nama ?? 'N/A' }}</td>
                                    <td>{{ $pengajuan->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $pengajuan->status }}">
                                            {{ ucfirst($pengajuan->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pendeta.pengajuan.show', $pengajuan->id) }}" class="btn-link">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada pengajuan</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Jemaat Terbaru --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-user-plus"></i>
                    Jemaat Terbaru
                </div>
                <a href="{{ route('pendeta.jemaat.index') }}" class="btn-link">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if($jemaatTerbaru->count() > 0)
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jemaatTerbaru as $jemaat)
                                <tr>
                                    <td>
                                        <strong>{{ $jemaat->nama }}</strong><br>
                                        <small style="color: #95a5a6;">
                                            {{ $jemaat->created_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $jemaat->approved ? 'badge-aktif' : 'badge-pending' }}">
                                            {{ $jemaat->approved ? 'Aktif' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <p>Belum ada jemaat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Status Sosial Distribution --}}
    @if($statusSosialData->count() > 0)
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-chart-pie"></i>
                Distribusi Status Sosial Jemaat
            </div>
        </div>
        <div class="card-body">
            <div class="status-distribution">
                @foreach($statusSosialData as $data)
                <div class="status-item">
                    <div class="status-value">{{ $data->total }}</div>
                    <div class="status-label">{{ $data->status_sosial }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection