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
        Schema::create('musibahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // contoh: kebakaran, banjir
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Hapus tabel jika di-rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('musibahs');
    }
};
