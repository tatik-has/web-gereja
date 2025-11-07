<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $fillable = ['jemaat_id', 'judul', 'keterangan', 'musibah_id', 'status'];

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class);
    }
    public function musibah()
    {
        return $this->belongsTo(Musibah::class);
    }
    public function perhitunganSmart()
    {
        return $this->hasOne(PerhitunganSmart::class);
    }
}