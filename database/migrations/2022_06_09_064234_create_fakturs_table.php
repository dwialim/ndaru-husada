<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaktursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fakturs', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi');
            $table->foreignId('pbf_id');
            $table->string('no_faktur_pbf');
            $table->integer('total_pembelian');
            $table->integer('materai')->nullable();
            $table->integer('persentase_dpp')->nullable();
            $table->integer('status_piutang');
            $table->date('jatuh_tempo');
            $table->string('notaFaktur')->nullable();
            $table->string('notaPembayaran')->nullable();
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
        Schema::dropIfExists('fakturs');
    }
}
