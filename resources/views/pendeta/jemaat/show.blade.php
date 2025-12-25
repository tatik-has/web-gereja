@extends('layouts.admin')

@section('title', 'Detail Data Jemaat')

<link rel="stylesheet" href="{{ asset('css/jemaat-detail.css') }}">

@section('content')
<div class="jemaat-detail-container">
    {{-- Tombol Kembali --}}
    <a href="{{ route('pendeta.jemaat.index') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Jemaat
    </a>

    {{-- Info Read-Only --}}
    <div class="info-readonly">
        <i class="fa-solid fa-info-circle"></i>
        <div class="info-text">
            <strong>Informasi:</strong> 
            Anda sedang melihat data jemaat dalam mode <strong>Read-Only</strong>. 
            Untuk mengedit data, hubungi Admin.
        </div>
    </div>

    {{-- Card Detail Jemaat --}}
    <div class="detail-card">
        <h2 class="card-header">
            <i class="fa-solid fa-user"></i> Detail Informasi Jemaat
        </h2>

        <div class="detail-grid">
            {{-- KOLOM 1: DATA PRIBADI & KONTAK --}}
            <div>
                <h3 class="section-header personal">
                    <i class="fa-solid fa-address-card"></i> Data Pribadi & Kontak
                </h3>

                <div class="field-item">
                    <label class="field-label">Nama Lengkap</label>
                    <p class="field-value large">{{ $jemaat->nama }}</p>
                </div>

                <div class="field-item">
                    <label class="field-label">Email</label>
                    <p class="field-value {{ $jemaat->email ? '' : 'empty' }}">
                        {{ $jemaat->email ?? 'Tidak ada email' }}
                    </p>
                </div>

                <div class="field-item">
                    <label class="field-label">No. HP / WhatsApp</label>
                    <p class="field-value {{ $jemaat->no_hp ? '' : 'empty' }}">
                        {{ $jemaat->no_hp ?? 'Tidak ada nomor' }}
                    </p>
                </div>

                <div class="field-item">
                    <label class="field-label">Alamat</label>
                    <p class="field-value {{ $jemaat->alamat ? '' : 'empty' }}">
                        {{ $jemaat->alamat ?? 'Alamat belum diisi' }}
                    </p>
                </div>

                <div class="field-item">
                    <label class="field-label">Usia</label>
                    <p class="field-value">{{ $jemaat->usia }} Tahun</p>
                </div>

                <div class="field-item">
                    <label class="field-label">Status Akun</label>
                    <div>
                        @if($jemaat->approved)
                            <span class="status-badge active">
                                <i class="fa-solid fa-check-circle"></i> Aktif
                            </span>
                        @else
                            <span class="status-badge inactive">
                                <i class="fa-solid fa-times-circle"></i> Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Kartu Keluarga --}}
                @if($jemaat->file_kk)
                    <div class="kk-container">
                        <div class="kk-label">
                            <i class="fa-solid fa-file-image"></i> Kartu Keluarga
                        </div>
                        @php
                            $ext = pathinfo($jemaat->file_kk, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                            <a href="{{ asset('storage/' . $jemaat->file_kk) }}" target="_blank">
                                <img src="{{ asset('storage/' . $jemaat->file_kk) }}" 
                                     alt="Kartu Keluarga" 
                                     class="kk-image">
                            </a>
                        @else
                            <a href="{{ asset('storage/' . $jemaat->file_kk) }}" 
                               target="_blank" 
                               class="btn-download">
                                <i class="fa-solid fa-file-pdf"></i> Download KK (PDF)
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- KOLOM 2: DATA EKONOMI & SOSIAL --}}
            <div>
                <h3 class="section-header economic">
                    <i class="fa-solid fa-briefcase"></i> Data Ekonomi & Sosial
                </h3>

                <div class="field-item">
                    <label class="field-label">Pekerjaan</label>
                    <p class="field-value" style="text-transform: capitalize;">
                        {{ $jemaat->pekerjaan }}
                    </p>
                </div>

                <div class="field-item">
                    <label class="field-label">Gaji per Bulan</label>
                    <p class="field-value">
                        @if($jemaat->gaji_per_bulan > 0)
                            <span class="salary-value">
                                Rp {{ number_format($jemaat->gaji_per_bulan, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="empty">Tidak ada gaji tetap</span>
                        @endif
                    </p>
                </div>

                <div class="field-item">
                    <label class="field-label">Status Sosial</label>
                    <p class="field-value" style="text-transform: capitalize;">
                        {{ $jemaat->status_sosial }}
                    </p>
                </div>

                <div class="field-item">
                    <label class="field-label">Jumlah Tanggungan</label>
                    <p class="field-value large">
                        {{ $jemaat->jumlah_tanggungan }} Orang
                    </p>
                </div>

                <div class="field-item">
                    <label class="field-label">Tanggal Bergabung</label>
                    <p class="field-value">
                        {{ \Carbon\Carbon::parse($jemaat->created_at)->format('d F Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Anggota Keluarga --}}
    <div class="family-table-container">
        <h3 class="family-header">
            <i class="fa-solid fa-users"></i> 
            Daftar Anggota Keluarga ({{ $jemaat->anggotaKeluarga->count() }} Orang)
        </h3>

        @if($jemaat->anggotaKeluarga->count() > 0)
            <table class="family-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Status Hubungan</th>
                        <th>Usia</th>
                        <th>Pekerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jemaat->anggotaKeluarga as $index => $anggota)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="font-weight: 600;">{{ $anggota->nama_anggota }}</td>
                            <td>
                                <span class="relation-badge">
                                    {{ $anggota->status_hubungan }}
                                </span>
                            </td>
                            <td>{{ $anggota->usia ?? '-' }} {{ $anggota->usia ? 'Tahun' : '' }}</td>
                            <td>{{ $anggota->pekerjaan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-users-slash"></i>
                <p>Belum ada data anggota keluarga</p>
            </div>
        @endif
    </div>
</div>

@endsection