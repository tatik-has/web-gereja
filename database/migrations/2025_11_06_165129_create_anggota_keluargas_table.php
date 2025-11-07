<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaKeluargasTable extends Migration
{
    public function up()
    {
        Schema::create('anggota_keluargas', function (Blueprint $table) {
            $table->id();
            
            // Ini adalah link ke Kepala Keluarga (tabel jemaats)
            $table->foreignId('jemaat_id')->constrained('jemaats')->onDelete('cascade');
            
            $table->string('nama_anggota');
            $table->string('status_hubungan'); // cth: Istri, Anak, Cucu
            $table->string('pekerjaan')->nullable();
            $table->integer('usia')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anggota_keluargas');
    }
}