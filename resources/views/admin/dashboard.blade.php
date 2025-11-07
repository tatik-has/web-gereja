@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <p>Ini adalah halaman ringkasan untuk admin.</p>
    
    <div class="dashboard-stats">
        <div class="stat-card blue">
            <h3>Total Jemaat</h3>
            <p>{{ $totalJemaat }}</p>
        </div>
        
        <div class="stat-card yellow">
            <h3>Pengajuan Baru</h3>
            <p>{{ $pengajuanBaru }}</p>
        </div>
        
        </div>
@endsection