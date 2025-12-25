@extends('layouts.admin') 

@section('title', 'Data Pengajuan Bantuan - Akses Pendeta')

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


    <!-- Table Container -->
    <div class="table-container">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengaju</th>
                    <th>Judul Pengajuan</th>
                    <th>Jenis Musibah</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $index => $pengajuan)
                    <tr>
                        <td class="no-cell">{{ $pengajuans->firstItem() + $index }}</td>
                        <td class="name-cell">{{ $pengajuan->jemaat->nama ?? 'Jemaat Tidak Ditemukan' }}</td>
                        <td class="jenis-cell">{{ $pengajuan->judul ?? '-' }}</td>
                        <td class="jenis-cell">{{ $pengajuan->musibah->nama_musibah ?? 'Tidak Ada Musibah' }}</td>
                        <td class="date-cell">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y') }}</td>
                        <td>
                            <span class="status-badge 
                                @if($pengajuan->status == 'pending') badge-warning
                                @elseif($pengajuan->status == 'disetujui' || $pengajuan->status == 'diterima') badge-success
                                @else badge-danger
                                @endif">
                                @if($pengajuan->status == 'pending')
                                    ⏳
                                @elseif($pengajuan->status == 'disetujui' || $pengajuan->status == 'diterima')
                                    ✓
                                @else
                                    ✗
                                @endif
                                {{ ucfirst($pengajuan->status) }}
                            </span>
                        </td>
                        <td class="aksi-cell">
                            <a href="{{ route('pendeta.pengajuan.show', $pengajuan->id) }}" class="btn-detail">
                                <span class="btn-icon">👁️</span>
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon">📋</div>
                                <p>Belum ada data pengajuan bantuan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $pengajuans->links() }}
    </div>
</div>
@endsection