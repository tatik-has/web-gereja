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
        Schema::table('perhitungan_smarts', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('nilai_per_kriteria');
            $table->text('alasan')->nullable()->after('kategori');
            $table->string('rekomendasi')->nullable()->after('alasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perhitungan_smarts', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'alasan', 'rekomendasi']);
        });
    }
};