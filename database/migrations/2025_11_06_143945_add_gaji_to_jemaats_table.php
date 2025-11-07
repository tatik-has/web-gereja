<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('jemaats', function (Blueprint $table) {
        // Tambahkan baris ini
        $table->decimal('gaji_per_bulan', 15, 2)->default(0)->after('pekerjaan');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jemaats', function (Blueprint $table) {
            //
        });
    }
};
