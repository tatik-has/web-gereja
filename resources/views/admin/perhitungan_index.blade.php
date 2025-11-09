@extends('layouts.admin')
@section('title', 'Perhitungan SMART')

@section('content')
    <h2>Perhitungan Bantuan (Metode SMART)</h2>
    
    <h3>Daftar Pengajuan (Pending)</h3>
    @forelse($pengajuans as $p)
      <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; border-radius: 5px;">
        <h4>{{ $p->judul }} - ({{ $p->jemaat->nama }})</h4>
        <p>Musibah: {{ $p->musibah->nama ?? 'N/A' }}</p>
        <form method="post" action="{{ route('admin.perhitungan.hitung') }}">
          @csrf
          <input type="hidden" name="pengajuan_id" value="{{ $p->id }}">
          <button type="submit" class="btn btn-primary">Hitung SMART</button>
        </form>
      </div>
    @empty
      <p>Tidak ada pengajuan yang menunggu perhitungan.</p>
    @endforelse
@endsection