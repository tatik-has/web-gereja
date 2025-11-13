<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Musibah;
use Illuminate\Support\Facades\DB;

class MusibahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Data ini SUDAH DISESUAIKAN dengan mapping di PerhitunganController
     * Setiap nama musibah harus PERSIS sama dengan yang ada di controller
     */
    public function run()
    {
        // Hapus data lama (opsional, hapus comment jika ingin reset data)
        // Musibah::truncate();

        // ============================================================
        // MUSIBAH PRIORITAS TINGGI (Nilai 30-25)
        // ============================================================
        
        Musibah::create([
            'nama' => 'Meninggal Dunia (Keluarga Inti)',
            'keterangan' => 'Bantuan duka cita untuk kematian anggota keluarga inti (orang tua, anak, pasangan)'
        ]);

        Musibah::create([
            'nama' => 'Kebakaran Rumah',
            'keterangan' => 'Kerusakan rumah akibat kebakaran - NILAI: 25'
        ]);

        // ============================================================
        // MUSIBAH PRIORITAS MENENGAH (Nilai 20-15)
        // ============================================================
        
        Musibah::create([
            'nama' => 'Sakit Berat / Opname',
            'keterangan' => 'Sakit berat yang memerlukan perawatan intensif di rumah sakit'
        ]);

        Musibah::create([
            'nama' => 'Banjir / Musibah Alam Ringan',
            'keterangan' => 'Bencana alam seperti banjir, tanah longsor ringan'
        ]);

        // ============================================================
        // MUSIBAH PRIORITAS RENDAH (Nilai 10)
        // ============================================================
        
        Musibah::create([
            'nama' => 'Sakit Ringan / Cedera',
            'keterangan' => 'Sakit atau cedera yang tidak memerlukan perawatan intensif'
        ]);

        // ============================================================
        // BANTUAN UMUM (Nilai 0)
        // ============================================================
        
        Musibah::create([
            'nama' => 'Tidak Mengalami Musibah',
            'keterangan' => 'Bantuan untuk kebutuhan umum tanpa musibah khusus'
        ]);
    }
}