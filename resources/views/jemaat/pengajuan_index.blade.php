@extends('layouts.app')
@section('title', 'Riwayat Pengajuan Saya')

@section('content')
    <a href="{{ route('jemaat.pengajuan.create') }}" class="btn btn-primary" style="margin-bottom: 20px;">
        <i class="fa-solid fa-plus"></i> Buat Pengajuan Baru
    </a>

    <table>
        <thead>
            <tr>
                <th>Tgl. Pengajuan</th>
                <th>Judul</th>
                <th>Jenis</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $p)
            <tr>
                <td>{{ $p->created_at->format('d M Y') }}</td>
                <td>{{ $p->judul }}</td>
                <td>{{ $p->musibah->nama ?? 'N/A' }}</td>
                <td>
                    @if($p->status == 'pending')
                        <span style="color: #f39c12; font-weight: bold;">Pending</span>
                    @elseif($p->status == 'diterima')
                        <span style="color: #2ecc71; font-weight: bold;">Diterima</span>
                    @else
                        <span style="color: #e74c3c; font-weight: bold;">Ditolak</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Anda belum memiliki riwayat pengajuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $pengajuans->links() }}
    </div>
@endsection