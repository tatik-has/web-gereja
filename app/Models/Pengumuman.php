<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    /**
     * TAMBAHKAN BARIS INI:
     * Memberitahu Laravel secara manual nama tabel yang benar,
     * karena tebakan otomatisnya salah.
     */
    protected $table = 'pengumumans';

    /**
     * Kolom yang bisa diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'isi',
        'tanggal',
    ];
}