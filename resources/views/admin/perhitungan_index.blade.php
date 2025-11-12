@extends('layouts.admin')
@section('title', 'Detail Pengajuan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Tombol Kembali -->
            <a href="{{ route('admin.perhitungan.halaman_hitung') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Halaman Perhitungan
            </a>

            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Detail Pengajuan: {{ $pengajuan->judul }}</h2>
                </div>
                <div class="card-body">

                    {{-- Alert untuk pesan sukses --}}
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- Alert untuk hasil perhitungan (dari redirect) --}}
                    @if(session('hasil'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading">📊 Hasil Perhitungan SMART (Baru Saja Dihitung)</h5>
                        <hr>
                        <p class="mb-1"><strong>Skor Akhir:</strong> {{ session('hasil')['skor'] }}</p>
                        <p class="mb-1">
                            <strong>Kategori:</strong> 
                            <span class="badge badge-{{ session('hasil')['kategori'] == 'Layak' ? 'success' : (session('hasil')['kategori'] == 'Kurang Layak' ? 'warning' : 'danger') }}">
                                {{ session('hasil')['kategori'] }}
                            </span>
                        </p>
                        <p class="mb-1"><strong>Rekomendasi:</strong> {{ session('hasil')['rekomendasi'] }}</p>
                        <p class="mb-0"><strong>Alasan:</strong> {{ session('hasil')['alasan'] }}</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- Jika belum dihitung, tampilkan tombol hitung --}}
                    {{-- PERBAIKAN: Menggunakan $pengajuan->perhitunganSmart --}}
                    @if(!$pengajuan->perhitunganSmart)
                    <div class="alert alert-warning text-center">
                        <h4 class="alert-heading">Perhitungan Belum Dilakukan</h4>
                        <p>Belum ada hasil perhitungan SMART untuk pengajuan ini.</p>
                        <form method="POST" action="{{ route('admin.perhitungan.hitung') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                            <button type="submit" class="btn btn-primary btn-lg" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghitung SMART untuk pengajuan ini?')">
                                <i class="fas fa-calculator"></i> Hitung SMART Sekarang
                            </button>
                        </form>
                    </div>
                    @endif

                    <div class="row">
                        <!-- Kolom Informasi Pengajuan -->
                        <div class="col-md-6">
                            <h4 class="mb-3">Informasi Pengajuan</h4>
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">Judul</th>
                                        <td>{{ $pengajuan->judul }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Musibah</th>
                                        <td>{{ $pengajuan->jenis_musibah ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>{{ $pengajuan->keterangan ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Diajukan</th>
                                        <td>{{ $pengajuan->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Saat Ini</th>
                                        <td><span class="badge badge-warning">{{ $pengajuan->status }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Aksi Admin</th>
                                        <td>
                                            {{-- Form untuk Menerima --}}
                                            <form action="{{ route('admin.pengajuan.updateStatus', $pengajuan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST') {{-- Atau PUT/PATCH jika route-nya demikian --}}
                                                <input type="hidden" name="status" value="diterima">
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Anda yakin ingin MENERIMA pengajuan ini?')">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                            </form>
                                            
                                            {{-- Form untuk Menolak --}}
                                            <form action="{{ route('admin.pengajuan.updateStatus', $pengajuan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="status" value="ditolak">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin MENOLAK pengajuan ini?')">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <h4 class="mb-3 mt-4">Data Jemaat Pemohon</h4>
                            @if($pengajuan->jemaat)
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">Nama</th>
                                        <td>{{ $pengajuan->jemaat->nama ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>{{ $pengajuan->jemaat->alamat ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pekerjaan</th>
                                        <td>{{ $pengajuan->jemaat->pekerjaan ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gaji/Bulan</th>
                                        <td>Rp {{ number_format($pengajuan->jemaat->penghasilAN ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Sosial</th>
                                        <td>{{ $pengajuan->jemaat->status_sosial ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Usia</th>
                                        <td>{{ $pengajuan->jemaat->usia ?? 'N/A' }} Tahun</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggungan</th>
                                        <td>{{ $pengajuan->jemaat->jumlah_tanggungan ?? '0' }} Orang</td>
                                    </tr>
                                </tbody>
                            </table>
                            @else
                            <div class="alert alert-danger">Data jemaat tidak ditemukan.</div>
                            @endif
                        </div>

                        <!-- Kolom Hasil Perhitungan SMART -->
                        <div class="col-md-6">
                            <h4 class="mb-3">Hasil Perhitungan SMART</h4>
                            
                            {{-- PERBAIKAN: Menggunakan $pengajuan->perhitunganSmart --}}
                            @if($pengajuan->perhitunganSmart)
                                @php
                                    // Ambil data perhitungan untuk ditampilkan
                                    $perhitunganSmart = $pengajuan->perhitunganSmart;
                                    $nilai_utility = $perhitunganSmart->nilai_per_kriteria ?? [];
                                @endphp
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th colspan="2">SKOR AKHIR</th>
                                            <th class="text-right" style="font-size: 1.25rem;">
                                                {{ number_format($perhitunganSmart->total_score, 4) }}
                                            </th>
                                        </tr>
                                        <tr class="bg-light">
                                            <th>Kriteria</th>
                                            <th>Bobot</th>
                                            <th>Nilai Utility (0-100)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>C1 (Kondisi Darurat)</td>
                                            <td>30%</td>
                                            <td>{{ isset($nilai_utility['C1']) ? number_format($nilai_utility['C1'], 2) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>C2 (Penghasilan)</td>
                                            <td>25%</td>
                                            <td>{{ isset($nilai_utility['C2']) ? number_format($nilai_utility['C2'], 2) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>C3 (Usia)</td>
                                            <td>15%</td>
                                            <td>{{ isset($nilai_utility['C3']) ? number_format($nilai_utility['C3'], 2) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>C4 (Status Sosial)</td>
                                            <td>15%</td>
                                            <td>{{ isset($nilai_utility['C4']) ? number_format($nilai_utility['C4'], 2) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>C5 (Tanggungan)</td>
                                            <td>15%</td>
                                            <td>{{ isset($nilai_utility['C5']) ? number_format($nilai_utility['C5'], 2) : 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <small class="form-text text-muted">
                                    * Nilai kriteria di atas adalah nilai utility (Ui) setelah normalisasi (Skala 0-100).
                                </small>

                                <!-- Rekomendasi Keputusan -->
                                <div class="card bg-light mt-4">
                                    <div class="card-header bg-{{ $perhitunganSmart->kategori == 'Layak' ? 'success' : ($perhitunganSmart->kategori == 'Kurang Layak' ? 'warning' : 'danger') }} text-white">
                                        <h5 class="mb-0">Rekomendasi Keputusan</h5>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $perhitunganSmart->rekomendasi ?? 'N/A' }}</h4>
                                        <p><strong>Kategori:</strong> {{ $perhitunganSmart->kategori ?? 'N/A' }}</p>
                                        <p class="card-text">
                                            <strong>Alasan:</strong> {{ $perhitunganSmart->alasan ?? 'Tidak ada alasan tersimpan.' }}
                                        </p>
                                    </div>
                                </div>
                            
                            @else
                                <div class="alert alert-secondary text-center">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p class="mb-0">Hasil perhitungan akan muncul di sini setelah Anda menekan tombol "Hitung SMART".</p>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        background-color: #f8f9fa;
    }
</style>
@endpush