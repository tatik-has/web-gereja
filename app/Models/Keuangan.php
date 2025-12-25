<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis',
        'kategori',
        'keterangan',
        'nominal',
        'tanggal',
        'file_bukti',
    ];

    // Helper untuk label kategori
    public function getKategoriLabelAttribute()
    {
        $labels = [
            'persembahan_umum' => 'Persembahan Umum',
            'ucapan_syukur' => 'Ucapan Syukur',
            'persepuluhan' => 'Persepuluhan',
            'lainnya' => 'Lainnya',
        ];

        return $labels[$this->kategori] ?? 'Lainnya';
    }
}