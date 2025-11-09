{{-- resources/views/jemaat/keuangan.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan Gereja - Sistem Gereja</title>

    <link rel="stylesheet" href="{{ asset('css/jemaat-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Style khusus untuk logo navbar jemaat */
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
    </style>
</head>

<body>

    {{-- Background Image dengan Overlay --}}
    <div class="background-overlay"></div>
    <div class="background-image" style="background-image: url('{{ asset('images/gereja.jpeg') }}');"></div>

    <div class="jemaat-layout-wrapper">

        {{-- NAVBAR HORIZONTAL MODERN --}}
        <header class="jemaat-navbar">
            <div class="jemaat-navbar-brand">
                <a href="{{ route('jemaat.dashboard') }}">
                    <img src="{{ asset('images/logo hkbp.png') }}" alt="Logo KHBP" class="jemaat-navbar-logo">
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

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header Halaman --}}
            <div style="margin-bottom: 30px; background: rgba(255, 255, 255, 0.95); padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h2 style="color: #2c3e50; margin: 0 0 10px 0; font-size: 1.8rem;">
                    <i class="fa-solid fa-wallet"></i> Keuangan Gereja
                </h2>
                <p style="color: #7f8c8d; margin: 0;">Transparansi laporan keuangan gereja untuk jemaat</p>
            </div>

            {{-- Card Statistik --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #2ecc71;">
                    <h3 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 0.9rem; font-weight: 500;">Total Pemasukan</h3>
                    <p style="margin: 0; font-size: 1.5rem; font-weight: bold; color: #2ecc71;">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</p>
                </div>
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #e74c3c;">
                    <h3 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 0.9rem; font-weight: 500;">Total Pengeluaran</h3>
                    <p style="margin: 0; font-size: 1.5rem; font-weight: bold; color: #e74c3c;">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</p>
                </div>
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid #3498db;">
                    <h3 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 0.9rem; font-weight: 500;">Saldo Akhir</h3>
                    <p style="margin: 0; font-size: 1.5rem; font-weight: bold; color: #3498db;">Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Info Footer --}}
            <div style="margin-top: 20px; padding: 15px; background: #ecf0f1; border-radius: 8px; color: #7f8c8d; font-size: 0.9rem;">
                <i class="fa-solid fa-info-circle"></i> 
                <strong>Catatan:</strong> Data keuangan ini bersifat transparan untuk seluruh jemaat. 
                Untuk pertanyaan lebih lanjut, silakan hubungi bendahara gereja.
            </div>

        </main>

    </div>

</body>

</html>