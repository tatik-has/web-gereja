<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pengajuan;

class PerhitunganSmart extends Model
{
    /**
     * Nama tabel yang terkait dengan model.
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
        'kategori',
        'rekomendasi',
        'alasan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nilai_per_kriteria' => 'array', 
    ];

    /**
     * Relasi ke model Pengajuan
     */
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}