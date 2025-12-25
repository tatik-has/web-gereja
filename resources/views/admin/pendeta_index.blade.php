@extends('layouts.admin')

@section('title', 'Kelola Data Pendeta')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Daftar Pendeta</h2>
        <a href="{{ route('admin.pendeta.create') }}" class="btn btn-primary">+ Tambah Pendeta</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendetas as $index => $pendeta)
                <tr>
                    <td>{{ $pendetas->firstItem() + $index }}</td>
                    <td>{{ $pendeta->name }}</td>
                    <td>{{ $pendeta->email }}</td>
                    <td>{{ $pendeta->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.pendeta.edit', $pendeta->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        
                        <form action="{{ route('admin.pendeta.destroy', $pendeta->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus pendeta ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data pendeta.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $pendetas->links() }}
@endsection