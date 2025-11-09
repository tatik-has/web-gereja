@extends('layouts.jemaat')
@section('title', 'Riwayat Pengajuan Saya')

@section('content')
    <!-- Action Button -->
    <a href="{{ route('jemaat.pengajuan.create') }}" class="btn btn-primary" style="margin-bottom: 20px;">
        <i class="fa-solid fa-plus"></i> Buat Pengajuan Baru
    </a>

    <!-- Table Container -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><i class="fa-solid fa-calendar-days"></i> Tanggal Pengajuan</th>
                    <th><i class="fa-solid fa-file-lines"></i> Judul</th>
                    <th><i class="fa-solid fa-layer-group"></i> Jenis</th>
                    <th><i class="fa-solid fa-circle-info"></i> Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $p)
                <tr>
                    <td>{{ $p->created_at->format('d M Y') }}</td>
                    <td><strong>{{ $p->judul }}</strong></td>
                    <td>{{ $p->musibah->nama ?? 'N/A' }}</td>
                    <td>
                        @if($p->status == 'pending')
                            <span class="status-badge status-pending">
                                <i class="fa-solid fa-clock"></i> Pending
                            </span>
                        @elseif($p->status == 'diterima')
                            <span class="status-badge status-diterima">
                                <i class="fa-solid fa-circle-check"></i> Diterima
                            </span>
                        @else
                            <span class="status-badge status-ditolak">
                                <i class="fa-solid fa-circle-xmark"></i> Ditolak
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state">
                        <div class="empty-icon">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <h3>Belum Ada Pengajuan</h3>
                        <p>Anda belum memiliki riwayat pengajuan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($pengajuans->hasPages())
    <div class="pagination-wrapper">
        {{ $pengajuans->links() }}
    </div>
    @endif
@endsection