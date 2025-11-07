<nav class="sidebar-nav">
    <ul>
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.jemaats.index') }}">
                <i class="fa-solid fa-users"></i> Data Jemaat
            </a>
        </li>
        <li>
            <a href="{{ route('admin.pengajuan.index') }}">
                <i class="fa-solid fa-file-invoice"></i> Data Pengajuan
            </a>
        </li>
        <li>
            <a href="{{ route('admin.pengumuman.index') }}">
                <i class="fa-solid fa-bullhorn"></i> Pengumuman
            </a>
        </li>
        <!-- <li>
            <a href="{{ route('admin.pengajuan.index') }}">
                <i class="fa-solid fa-bullhorn"></i> pengajuan Bantuan
            </a>
        </li> -->
        <li>
            <a href="{{ route('admin.keuangan.index') }}">
                <i class="fa-solid fa-wallet"></i> Keuangan Gereja
            </a>
        </li>
    </ul>
</nav>