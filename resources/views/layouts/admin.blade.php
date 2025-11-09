<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Sistem Gereja</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Style khusus untuk logo */
        .sidebar-header a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 50%;
            background-color: white;
            padding: 5px;
        }

        .sidebar-header span {
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            line-height: 1.3;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="#">
                {{-- Logo di atas --}}
                <img src="{{ asset('images/logo hkbp.png') }}" alt="Logo HKBP" class="sidebar-logo">
                {{-- Teks di bawah logo --}}
                <span>GEREJA HKBP BENGKALIS</span>
            </a>
        </div>

        {{-- Hanya memuat navigasi admin --}}
        @include('layouts.partials.admin_nav')

    </aside>

    <div class="main-wrapper">

        <header class="topbar">
            <div class="topbar-user">
                <span>
                    {{-- Langsung tampilkan nama admin --}}
                    {{ Auth::guard('admin')->user()->name }} (Admin)
                </span>

                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="content">
            <div class="content-header">
                <h2>@yield('title')</h2>
            </div>

            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Oops!</strong> Terjadi beberapa masalah:
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>
</body>

</html>