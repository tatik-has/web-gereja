@extends('layouts.admin')
@section('title', 'Data Jemaat')

@section('content')
    <h2>Manajemen Data Jemaat</h2>
    <a href="{{ route('admin.jemaats.create') }}" class="btn btn-primary" style="margin-bottom: 20px; display: inline-block;">Tambah Jemaat Baru</a>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success"
            style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Approved</th>
          <th style="width: 300px;">Aksi</th> {{-- Lebarkan kolom aksi --}}
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
              <div style="display: flex; gap: 5px;"> {{-- Bikin jadi flexbox --}}
                <a href="{{ route('admin.jemaats.edit', $j->id) }}" class="btn btn-secondary" style="padding: 5px 10px;">Edit</a>
                
                <a href="{{ route('admin.anggota.index', $j) }}" class="btn btn-primary" style="padding: 5px 10px;">
                    Anggota ({{ $j->anggota_keluarga_count }})
                </a>

                {{-- === TOMBOL HAPUS DITAMBAHKAN DI SINI === --}}
                <form action="{{ route('admin.jemaats.destroy', $j->id) }}" method="POST" style="display: inline-block; margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px;" 
                            onclick="return confirm('Anda yakin ingin menghapus data jemaat ini? Tindakan ini juga akan menghapus data pengajuan dan data anggota keluarga yang terkait.')">
                        Hapus
                    </button>
                </form>
                {{-- === AKHIR BAGIAN TOMBOL HAPUS === --}}
              </div>
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

    {{-- Style Tambahan untuk Tombol --}}
    <style>
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
    </style>
@endsection