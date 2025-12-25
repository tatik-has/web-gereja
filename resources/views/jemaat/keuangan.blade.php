<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan Gereja - Sistem Gereja</title>

    <link rel="stylesheet" href="{{ asset('css/jemaat-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .jemaat-navbar-brand a {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .jemaat-navbar-logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
            border-radius: 50%;
            background-color: white;
            padding: 3px;
        }

        .jemaat-navbar-brand span {
            font-size: 1.3rem;
            font-weight: 600;
        }

        /* Style untuk card statistik */
        .keuangan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .keuangan-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .keuangan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .keuangan-card h3 {
            font-size: 1rem;
            margin: 0 0 15px 0;
            font-weight: 600;
        }

        .keuangan-card .amount {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .keuangan-card small {
            font-size: 0.85rem;
            color: #7f8c8d;
        }

        /* Tabel transaksi */
        .transaksi-table {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .transaksi-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .transaksi-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #dee2e6;
        }

        .transaksi-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .transaksi-table tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-pemasukan {
            background: #d4edda;
            color: #155724;
        }

        .badge-pengeluaran {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>

    {{-- Background Image dengan Overlay --}}
    <div class="background-overlay"></div>
    <div class="background-image" style="background-image: url('{{ asset('images/gereja.jpeg') }}');"></div>

    <div class="jemaat-layout-wrapper">

        {{-- NAVBAR --}}
        <header class="jemaat-navbar">
            <div class="jemaat-navbar-brand">
                <a href="{{ route('jemaat.dashboard') }}">
                    <img src="{{ asset('images/logo hkbp.png') }}" alt="Logo HKBP" class="jemaat-navbar-logo">
                    <span>GEREJA HKBP BENGKALIS</span>
                </a>
            </div>

            <nav class="jemaat-nav-links">
                <ul>
                    <li>
                        <a href="{{ route('jemaat.dashboard') }}">
                            <i class="fa-solid fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jemaat.pengajuan.create') }}">
                            <i class="fa-solid fa-file-pen"></i> Buat Pengajuan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jemaat.pengajuan.index') }}">
                            <i class="fa-solid fa-history"></i> Riwayat Pengajuan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jemaat.keuangan') }}" class="active">
                            <i class="fa-solid fa-wallet"></i> Keuangan Gereja
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="jemaat-user-menu">
                <div class="user-info">
                    <i class="fa-solid fa-user-circle"></i>
                    <span>{{ Auth::user()->nama }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- KONTEN UTAMA --}}
        <main class="jemaat-content">

            {{-- Header Halaman --}}
            <div style="margin-bottom: 30px; background: rgba(255, 255, 255, 0.95); padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h2 style="color: #2c3e50; margin: 0 0 10px 0; font-size: 2rem;">
                    <i class="fa-solid fa-wallet"></i> Keuangan Gereja
                </h2>
                <p style="color: #7f8c8d; margin: 0;">Transparansi laporan keuangan gereja untuk jemaat</p>
            </div>

            {{-- === 3 KOTAK KATEGORI PEMASUKAN === --}}
            <div class="keuangan-grid">
                <div class="keuangan-card" style="border-left: 5px solid #3498db;">
                    <h3 style="color: #3498db;">
                        <i class="fa-solid fa-hand-holding-heart"></i> Persembahan Umum
                    </h3>
                    <div class="amount" style="color: #2c3e50;">
                        Rp {{ number_format($persembahan_umum, 0, ',', '.') }}
                    </div>
                    <small>
                        Saldo setelah pengeluaran: 
                        <strong style="color: {{ $saldo_persembahan_umum >= 0 ? '#27ae60' : '#e74c3c' }};">
                            Rp {{ number_format($saldo_persembahan_umum, 0, ',', '.') }}
                        </strong>
                    </small>
                </div>

                <div class="keuangan-card" style="border-left: 5px solid #9b59b6;">
                    <h3 style="color: #9b59b6;">
                        <i class="fa-solid fa-praying-hands"></i> Ucapan Syukur
                    </h3>
                    <div class="amount" style="color: #2c3e50;">
                        Rp {{ number_format($ucapan_syukur, 0, ',', '.') }}
                    </div>
                    <small>Dana khusus ucapan syukur jemaat</small>
                </div>

                <div class="keuangan-card" style="border-left: 5px solid #e67e22;">
                    <h3 style="color: #e67e22;">
                        <i class="fa-solid fa-percentage"></i> Persepuluhan
                    </h3>
                    <div class="amount" style="color: #2c3e50;">
                        Rp {{ number_format($persepuluhan, 0, ',', '.') }}
                    </div>
                    <small>Dana persepuluhan (10%) dari jemaat</small>
                </div>
            </div>

            {{-- === RINGKASAN TOTAL === --}}
            <div class="keuangan-grid" style="margin-bottom: 30px;">
                <div class="keuangan-card" style="border-left: 5px solid #2ecc71;">
                    <h3 style="color: #2ecc71;">
                        <i class="fa-solid fa-arrow-trend-up"></i> Total Pemasukan
                    </h3>
                    <div class="amount" style="color: #2ecc71;">
                        Rp {{ number_format($total_pemasukan, 0, ',', '.') }}
                    </div>
                    <small>Jumlah seluruh pemasukan</small>
                </div>

                <div class="keuangan-card" style="border-left: 5px solid #e74c3c;">
                    <h3 style="color: #e74c3c;">
                        <i class="fa-solid fa-arrow-trend-down"></i> Total Pengeluaran
                    </h3>
                    <div class="amount" style="color: #e74c3c;">
                        Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}
                    </div>
                    <small>*Diambil dari Persembahan Umum</small>
                </div>

                <div class="keuangan-card" style="border-left: 5px solid #3498db;">
                    <h3 style="color: #3498db;">
                        <i class="fa-solid fa-sack-dollar"></i> Saldo Akhir
                    </h3>
                    <div class="amount" style="color: {{ $saldo_akhir >= 0 ? '#27ae60' : '#e74c3c' }};">
                        Rp {{ number_format($saldo_akhir, 0, ',', '.') }}
                    </div>
                    <small>Total saldo keseluruhan</small>
                </div>
            </div>

            {{-- === TABEL RIWAYAT TRANSAKSI === --}}
            <div class="transaksi-table">
                <h3 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 1.3rem;">
                    <i class="fa-solid fa-list"></i> Riwayat Transaksi
                </h3>
                
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis / Kategori</th>
                            <th>Keterangan</th>
                            <th style="text-align: right;">Nominal</th>
                            <th style="text-align: center;">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $trx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y') }}</td>
                            <td>
                                @if($trx->jenis == 'pemasukan')
                                    <span class="badge badge-pemasukan">📈 Pemasukan</span>
                                    <br>
                                    <small style="color: #7f8c8d;">{{ $trx->kategori_label }}</small>
                                @else
                                    <span class="badge badge-pengeluaran">📉 Pengeluaran</span>
                                    <br>
                                    <small style="color: #7f8c8d;">Dari: Persembahan Umum</small>
                                @endif
                            </td>
                            <td>{{ $trx->keterangan }}</td>
                            <td style="text-align: right; font-weight: 600;">
                                Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                @if($trx->file_bukti)
                                    @php
                                        $ext = pathinfo($trx->file_bukti, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                        <a href="{{ asset('storage/' . $trx->file_bukti) }}" target="_blank" 
                                           style="color: #3498db; text-decoration: none;">
                                            <i class="fa-solid fa-image"></i> Lihat
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $trx->file_bukti) }}" target="_blank" 
                                           style="color: #e74c3c; text-decoration: none;">
                                            <i class="fa-solid fa-file-pdf"></i> PDF
                                        </a>
                                    @endif
                                @else
                                    <span style="color: #95a5a6;">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #7f8c8d; padding: 30px;">
                                <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                <br>
                                Belum ada data transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="margin-top: 20px;">
                    {{ $transaksis->links() }}
                </div>
            </div>

            {{-- Info Footer --}}
            <div style="margin-top: 30px; padding: 20px; background: rgba(236, 240, 241, 0.95); border-radius: 12px; color: #7f8c8d;">
                <i class="fa-solid fa-info-circle"></i> 
                <strong>Catatan:</strong> Data keuangan ini bersifat transparan untuk seluruh jemaat. 
                Untuk pertanyaan lebih lanjut, silakan hubungi bendahara gereja.
            </div>

        </main>

    </div>

</body>

</html>