@extends('layouts.app')

@section('title', 'Data Pengajuan Bantuan')

@section('content')
    <table>
      <thead>
          <tr>
            <th>ID</th>
            <th>Nama Jemaat</th>
            <th>Judul Pengajuan</th>
            <th>Jenis Musibah</th>
            <th>Tgl. Pengajuan</th>
            <th>Status</th>
            <th>Skor SMART</th>
            <th>Aksi</th>
          </tr>
      </thead>
      <tbody>
        @forelse($pengajuans as $p)
          <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->jemaat->nama ?? 'N/A' }}</td>
            <td>{{ $p->judul }}</td>
            <td>{{ $p->musibah->nama ?? 'N/A' }}</td>
            <td>{{ $p->created_at->format('d M Y') }}</td>
            <td>
                @if($p->status == 'pending')
                    <span style="color: #f39c12; font-weight: bold;">Pending</span>
                @elseif($p->status == 'diterima')
                    <span style="color: #2ecc71; font-weight: bold;">Diterima</span>
                @else
                    <span style="color: #e74c3c; font-weight: bold;">Ditolak</span>
                @endif
            </td>
            <td>
                {{ $p->perhitunganSmart->total_score ?? 'Belum dihitung' }}
            </td>
            <td>
                <a href="{{ route('admin.pengajuan.show', $p->id) }}" class="btn btn-secondary" style="padding: 5px 10px;">
                    Detail
                </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center;">Belum ada data pengajuan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $pengajuans->links() }}
    </div>
@endsection