<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Gereja</title>

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
            /* Membuat bulat */
            background-color: white;
            /* Opsional: background putih */
            padding: 3px;
            /* Opsional: beri jarak dalam */
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
                    {{-- Ganti dengan path logo Anda --}}
                    <img src="{{ asset('images/logo hkbp.png') }}" alt="Logo KHBP" class="jemaat-navbar-logo">
                    <span>GEREJA HKBP BENGKALIS</span>
                </a>
            </div>

            <nav class="jemaat-nav-links">
                <ul>
                    <li>
                        <a href="{{ route('jemaat.dashboard') }}"
                            class="{{ request()->routeIs('jemaat.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jemaat.pengajuan.create') }}"
                            class="{{ request()->routeIs('jemaat.pengajuan.create') ? 'active' : '' }}">
                            <i class="fa-solid fa-file-pen"></i> Buat Pengajuan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jemaat.pengajuan.index') }}"
                            class="{{ request()->routeIs('jemaat.pengajuan.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-history"></i> Riwayat Pengajuan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jemaat.keuangan') }}"
                            class="{{ request()->routeIs('jemaat.keuangan') ? 'active' : '' }}">
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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <strong>Oops!</strong> Terjadi beberapa masalah:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Content dari Child View --}}
            @yield('content')

        </main>

    </div>

</body>

</html>