<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Musibah;

class MusibahSeeder extends Seeder
{
    public function run()
    {
        Musibah::create(['nama' => 'Meninggal', 'keterangan' => 'Bantuan duka cita']);
        Musibah::create(['nama' => 'Kebakaran', 'keterangan' => 'Kerusakan rumah akibat kebakaran']);
        Musibah::create(['nama' => 'Sakit Berat', 'keterangan' => 'Perlu biaya rawat inap']);
        Musibah::create(['nama' => 'Banjir', 'keterangan' => 'Kerusakan properti akibat banjir']);
        Musibah::create(['nama' => 'Sakit Ringan', 'keterangan' => 'Biaya berobat jalan']);
    }
}