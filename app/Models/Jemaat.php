<?php
namespace App\Models;

// HAPUS 'use Illuminate\Contracts\Auth\MustVerifyEmail;'

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// HAPUS 'implements MustVerifyEmail'
class Jemaat extends Authenticatable 
{
    use Notifiable;
    protected $guard = 'web'; 
    
    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'pekerjaan',
        'gaji_per_bulan',
        'usia',
        'password',
        'approved',
        'status_sosial',
        'file_kk'
    ];
    
    protected $hidden = ['password', 'remember_token'];

    public function anggotaKeluarga()
    {
        return $this->hasMany(AnggotaKeluarga::class);
    }

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }
    
    public function laporanMusibahs()
    {
        return $this->hasMany(LaporanMusibah::class);
    }
}