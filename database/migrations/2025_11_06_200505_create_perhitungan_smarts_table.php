<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perhitungan_smarts', function (Blueprint $table) {
            $table->id();
            

            $table->foreignId('pengajuan_id')
                  ->constrained('pengajuans')
                  ->onDelete('cascade'); // Jika pengajuan dihapus, perhitungan ikut terhapus
            
            $table->decimal('total_score', 8, 4)->nullable();

            // Kolom untuk menyimpan detail nilai per kriteria (C1, C2, dst.)
            // Kita simpan sebagai JSON agar fleksibel
            $table->json('nilai_per_kriteria')->nullable();

            $table->timestamps();

            $table->unique('pengajuan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perhitungan_smarts');
    }
};