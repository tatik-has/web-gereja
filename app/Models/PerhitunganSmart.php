<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pengajuan; // Relasi ke Pengajuan

class PerhitunganSmart extends Model
{
    /**
     * Nama tabel yang terkait dengan model.
     * Secara default, Laravel akan menganggap 'perhitungan_smarts'.
     * @var string
     */
    protected $table = 'perhitungan_smarts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pengajuan_id',
        'total_score',
        'nilai_per_kriteria',
        // tambahkan field lain jika Anda perlukan
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Ini SANGAT PENTING agar 'nilai_per_kriteria'
        // otomatis diubah dari JSON (di DB) ke array (di PHP)
        'nilai_per_kriteria' => 'array', 
    ];

    /**
     * Relasi ke model Pengajuan (pemilik perhitungan ini).
     */
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}