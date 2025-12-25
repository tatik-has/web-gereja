@extends('layouts.admin')

@section('title', isset($pendeta) ? 'Edit Data Pendeta' : 'Tambah Pendeta Baru')

@section('content')
    
    <form action="{{ isset($pendeta) ? route('admin.pendeta.update', $pendeta->id) : route('admin.pendeta.store') }}" method="POST">
        @csrf
        
        @if(isset($pendeta))
            @method('PUT')
        @endif
        
        <div class="form-group">
            <label for="name">Nama Lengkap Pendeta</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="{{ old('name', $pendeta->name ?? '') }}" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email (Untuk Login)</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="{{ old('email', $pendeta->email ?? '') }}" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="{{ isset($pendeta) ? 'Kosongkan jika tidak diubah' : 'Wajib diisi' }}">
            
            @if(!isset($pendeta))
                <small style="color: #555;">Password wajib diisi untuk pendeta baru.</small>
            @endif
        </div>

        <hr style="margin-top: 20px; margin-bottom: 20px;">

        <button type="submit" class="btn btn-primary">{{ isset($pendeta) ? 'Update Data' : 'Simpan Pendeta' }}</button>
        <a href="{{ route('admin.pendeta.index') }}" class="btn btn-secondary">Batal</a>
        
    </form>

@endsection