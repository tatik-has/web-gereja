@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman')

@section('content')
    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary" style="margin-bottom: 20px;">
        <i class="fa-solid fa-plus"></i> Buat Pengumuman Baru
    </a>

    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tanggal</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengumumans as $p)
            <tr>
                <td>{{ $p->judul }}</td>
                <td>{{ $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('d M Y') : $p->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.pengumuman.edit', $p->id) }}" class="btn btn-secondary" style="padding: 5px 10px;">Edit</a>
                    <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background-color: #e74c3c; color: white; padding: 5px 10px;">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Belum ada pengumuman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $pengumumans->links() }}
    </div>
@endsection