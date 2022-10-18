<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id');
            $table->string('barcode')->nullable();
            $table->integer('jumlah_box')->nullable();
            $table->integer('jumlah_perbox')->nullable();
            $table->integer('jumlah');
            $table->integer('stok_awal');
            $table->string('no_batch');
            $table->integer('harga_beli');
            $table->integer('harga_umum');
            $table->integer('harga_resep');
            $table->integer('harga_dispensing');
            $table->integer('harga_dispensing_perbiji');
            $table->date('expired');
            $table->date('tgl_masuk');
            $table->integer('minimal_stok')->nullable();
            $table->float('diskon', 8, 0)->nullable();
            $table->integer('potongan')->nullable();
            $table->integer('faktur_id')->nullable();
            $table->integer('nominal_laba')->nullable();
            $table->foreignId('pajak_id')->nullable();
            $table->integer('nominal_pajak')->nullable();
            $table->foreignId('pbf_id')->nullable();
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
        Schema::dropIfExists('stok_barangs');
    }
}
