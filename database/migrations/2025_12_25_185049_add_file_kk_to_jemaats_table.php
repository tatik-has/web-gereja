<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileKkToJemaatsTable extends Migration
{
    public function up()
    {
        Schema::table('jemaats', function (Blueprint $table) {
            $table->string('file_kk')->nullable()->after('jumlah_tanggungan');
        });
    }

    public function down()
    {
        Schema::table('jemaats', function (Blueprint $table) {
            $table->dropColumn('file_kk');
        });
    }
}