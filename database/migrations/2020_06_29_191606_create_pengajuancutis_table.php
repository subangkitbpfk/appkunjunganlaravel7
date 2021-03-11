<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuancutisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuancutis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pegawai_id');
            $table->integer('jeniscuti_id')->unsigned();
            $table->string('alasan_cuti');
            $table->string('alamat_selama_cuti');
            $table->date('tanggal_surat');
            $table->integer('masakerja'); // kontrak awal sampai sekarang
            $table->date('mulai_tanggal');
            $table->date('sampai_tanggal');
            $table->integer('sisacuti');
            $table->integer('status_pegawai'); // default 0
            $table->integer('status_kepaladivisi'); 
            $table->integer('status_kepegawaian');
            $table->integer('status_kepalakantor');
            $table->timestamps();

        });

         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuancutis');
    }
}
