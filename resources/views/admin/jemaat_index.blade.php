@extends('layouts.app')
@section('title', 'Data Jemaat')

@section('content')
    <h2>Manajemen Data Jemaat</h2>
    <a href="{{ route('admin.jemaats.create') }}" class="btn btn-primary" style="margin-bottom: 20px; display: inline-block;">Tambah Jemaat Baru</a>

    <table>
      <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Approved</th>
            <th>Aksi</th>
          </tr>
      </thead>
      <tbody>
        @forelse($jemaats as $j)
          <tr>
            <td>{{ $j->id }}</td>
            <td>{{ $j->nama }}</td>
            <td>{{ $j->email }}</td>
            <td>
                @if($j->approved)
                    <span style="color: green; font-weight: bold;">Ya</span>
                @else
                    <span style="color: red; font-weight: bold;">Tidak</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.jemaats.edit', $j->id) }}" class="btn btn-secondary" style="padding: 5px 10px;">Edit</a>
                <a href="{{ route('admin.anggota.index', $j) }}" class="btn btn-primary" style="padding: 5px 10px;">
                    Anggota ({{ $j->anggota_keluarga_count }})
                </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center;">Belum ada data jemaat.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    
    {{ $jemaats->links() }}
@endsection