@extends('layouts.admin') 

@section('title', 'Data Jemaat - Akses Pendeta')

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
                    <th>Nama Kepala Keluarga</th>
                    <th>Email</th>
                    <th>Usia</th>
                    <th>Pekerjaan</th>
                    <th>Status Sosial</th>
                    <th>Jumlah Anggota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jemaats as $index => $jemaat)
                    <tr>
                        <td class="no-cell">{{ $jemaats->firstItem() + $index }}</td>
                        <td class="name-cell">{{ $jemaat->nama }}</td>
                        <td class="email-cell">{{ $jemaat->email }}</td>
                        <td class="age-cell">{{ $jemaat->usia }} Tahun</td>
                        <td class="job-cell">{{ $jemaat->pekerjaan }}</td>
                        <td>
                            <span class="status-badge">{{ $jemaat->status_sosial }}</span>
                        </td>
                        <td class="count-cell">{{ $jemaat->anggota_keluarga_count }}</td>
                        <td class="aksi-cell">
                            <a href="{{ route('pendeta.jemaat.show', $jemaat->id) }}" class="btn-detail">
                                <span class="btn-icon">👁️</span>
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon">📋</div>
                                <p>Belum ada data jemaat yang terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $jemaats->links() }}
    </div>
</div>
@endsection