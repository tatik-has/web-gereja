<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/dashboard-jemaat.css') }}">
    
    <title>@yield('title', 'Admin Panel') - Sistem Gereja</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="#">
                <i class="fa-solid fa-church"></i> 
                <span>MyGereja</span>
            </a>
            </div>

        @auth('admin')
            @include('layouts.partials.admin_nav')
        @elseauth
            @include('layouts.partials.jemaat_nav')
        @endauth
    </aside>

    <div class="main-wrapper">
        
        <header class="topbar">
            <div class="topbar-user">
                <span>
                    @auth('admin')
                        {{ Auth::guard('admin')->user()->name }} (Admin)
                    @elseauth
                        {{ Auth::user()->nama }} (Jemaat)
                    @endauth
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
        
    </div> </body>
</html>