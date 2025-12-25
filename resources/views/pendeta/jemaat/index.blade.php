@extends('layouts.admin')

@section('title', 'Data Jemaat')

@section('content')
    {{-- Info Read-Only --}}
    <div style="margin-bottom: 20px; padding: 15px; background: #fff3cd; border-left: 5px solid #ffc107; border-radius: 5px;">
        <i class="fa-solid fa-info-circle" style="color: #856404;"></i> 
        <strong style="color: #856404;">Informasi:</strong> 
        <span style="color: #856404;">
            Anda sedang melihat data jemaat dalam mode <strong>Read-Only</strong>. 
            Untuk menambah/mengedit/menghapus data, hubungi Admin.
        </span>
    </div>

    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fa-solid fa-users"></i> Daftar Jemaat
    </h2>

    {{-- Statistik Ringkas --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid #3498db;">
            <h3 style="font-size: 0.9rem; color: #7f8c8d; margin: 0 0 10px 0;">Total Jemaat</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #3498db; margin: 0;">{{ $jemaats->total() }}</p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid #27ae60;">
            <h3 style="font-size: 0.9rem; color: #7f8c8d; margin: 0 0 10px 0;">Akun Aktif</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #27ae60; margin: 0;">
                {{ $jemaats->where('approved', true)->count() }}
            </p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid #e74c3c;">
            <h3 style="font-size: 0.9rem; color: #7f8c8d; margin: 0 0 10px 0;">Akun Tidak Aktif</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #e74c3c; margin: 0;">
                {{ $jemaats->where('approved', false)->count() }}
            </p>
        </div>
    </div>

    {{-- Tabel Data Jemaat --}}
    <table style="width: 100%; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <thead style="background: #f8f9fa;">
            <tr>
                <th style="padding: 15px; text-align: left;">ID</th>
                <th style="padding: 15px; text-align: left;">Nama</th>
                <th style="padding: 15px; text-align: left;">Email</th>
                <th style="padding: 15px; text-align: left;">Pekerjaan</th>
                <th style="padding: 15px; text-align: center;">Status</th>
                <th style="padding: 15px; text-align: center;">Anggota Keluarga</th>
                <th style="padding: 15px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jemaats as $j)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 12px;">{{ $j->id }}</td>
                    <td style="padding: 12px; font-weight: 600;">{{ $j->nama }}</td>
                    <td style="padding: 12px;">{{ $j->email ?? '-' }}</td>
                    <td style="padding: 12px; text-transform: capitalize;">{{ $j->pekerjaan }}</td>
                    <td style="padding: 12px; text-align: center;">
                        @if($j->approved)
                            <span style="background: #d4edda; color: #155724; padding: 5px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                <i class="fa-solid fa-check-circle"></i> Aktif
                            </span>
                        @else
                            <span style="background: #f8d7da; color: #721c24; padding: 5px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                <i class="fa-solid fa-times-circle"></i> Tidak Aktif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <span style="background: #e3f2fd; color: #1976d2; padding: 5px 12px; border-radius: 12px; font-weight: 600;">
                            {{ $j->anggota_keluarga_count }} Orang
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="{{ route('pendeta.jemaat.show', $j->id) }}" 
                           class="btn btn-primary" 
                           style="padding: 6px 15px; font-size: 0.9rem;">
                            <i class="fa-solid fa-eye"></i> Lihat Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #7f8c8d;">
                        <i class="fa-solid fa-users-slash" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                        <br>
                        Belum ada data jemaat
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $jemaats->links() }}
    </div>
@endsection