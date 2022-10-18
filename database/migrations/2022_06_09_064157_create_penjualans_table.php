<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('nama_pelanggan')->nullable();
            $table->string('no_kwitansi')->nullable();
            $table->string('jumlah_bayar')->nullable();
            $table->string('nama_pasien')->nullable();
            $table->string('umur_pasien')->nullable();
            $table->string('alamat_pasien')->nullable();
            $table->string('nama_dokter')->nullable();
            $table->string('nomor_resep')->nullable();
            $table->date('tanggal_resep')->nullable();
            $table->string('kembalian')->nullable();
            $table->date('tanggal_penjualan');
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
        Schema::dropIfExists('penjualans');
    }
}
