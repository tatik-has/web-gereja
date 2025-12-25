<nav class="sidebar-nav">
    <ul>
        {{-- 1. Dashboard Pendeta --}}
        <li class="{{ request()->routeIs('pendeta.dashboard') ? 'active' : '' }}">
            <a href="{{ route('pendeta.dashboard') }}">
                <i class="fa-solid fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        
        {{-- 2. Data Jemaat (Hanya Lihat) --}}
        <li class="{{ request()->routeIs('pendeta.jemaat.*') ? 'active' : '' }}">
            <a href="{{ route('pendeta.jemaat.index') }}">
                <i class="fa-solid fa-users"></i> Data Jemaat
            </a>
        </li>
        
        {{-- 3. Data Pengajuan (Hanya Lihat) --}}
        <li class="{{ request()->routeIs('pendeta.pengajuan.*') ? 'active' : '' }}">
            <a href="{{ route('pendeta.pengajuan.index') }}">
                <i class="fa-solid fa-file-invoice"></i> Data Pengajuan
            </a>
        </li>
        
        {{-- 4. Laporan Keuangan Gereja (Hanya Lihat) --}}
        <li class="{{ request()->routeIs('pendeta.keuangan.*') ? 'active' : '' }}">
            <a href="{{ route('pendeta.keuangan.index') }}">
                <i class="fa-solid fa-wallet"></i> Keuangan Gereja
            </a>
        </li>
        
        {{-- Catatan: Menu seperti 'Kelola Pendeta', 'Pengumuman' (CRUD), atau 'Perhitungan' dihilangkan --}}
        
    </ul>
</nav>