<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keuangans', function (Blueprint $table) {
            // Tambah kolom kategori pemasukan
            $table->enum('kategori', [
                'persembahan_umum', 
                'ucapan_syukur', 
                'persepuluhan',
                'lainnya'
            ])->default('lainnya')->after('jenis');
            
            // Tambah kolom file bukti
            $table->string('file_bukti')->nullable()->after('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('keuangans', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'file_bukti']);
        });
    }
};