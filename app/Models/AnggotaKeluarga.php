<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'jemaat_id', 'nama_anggota', 'status_hubungan', 'pekerjaan', 'usia'
    ];

    /**
     * Relasi: Anggota ini milik satu Kepala Keluarga (Jemaat)
     */
    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class);
    }
}