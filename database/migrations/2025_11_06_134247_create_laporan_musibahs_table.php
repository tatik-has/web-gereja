<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('laporan_musibahs', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel jemaats
            $table->foreignId('jemaat_id')
                ->constrained('jemaats')
                ->onDelete('cascade');

            // Relasi ke tabel musibahs
            $table->foreignId('musibah_id')
                ->constrained('musibahs')
                ->onDelete('cascade');

            $table->text('detail')->nullable();
            $table->date('tanggal')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Hapus tabel jika di-rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_musibahs');
    }
};
