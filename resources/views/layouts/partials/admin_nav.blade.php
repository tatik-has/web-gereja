<nav class="sidebar-nav">
    <ul>
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        
        <li class="{{ request()->routeIs('admin.pendeta.*') ? 'active' : '' }}">
            <a href="{{ route('admin.pendeta.index') }}">
                <i class="fa-solid fa-user-tie"></i> Kelola Pendeta
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.jemaats.*') ? 'active' : '' }}">
            <a href="{{ route('admin.jemaats.index') }}">
                <i class="fa-solid fa-users"></i> Data Jemaat
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
            <a href="{{ route('admin.pengajuan.index') }}">
                <i class="fa-solid fa-file-invoice"></i> Data Pengajuan
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
            <a href="{{ route('admin.pengumuman.index') }}">
                <i class="fa-solid fa-bullhorn"></i> Pengumuman
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.keuangan.*') ? 'active' : '' }}">
            <a href="{{ route('admin.keuangan.index') }}">
                <i class="fa-solid fa-wallet"></i> Keuangan Gereja
            </a>
        </li>
    </ul>
</nav>