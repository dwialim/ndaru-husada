<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailRetursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_returs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_id');
            $table->foreignId('stok_barang_id');
            $table->foreignId('detail_penjualan_id');
            $table->text('deskripsi')->nullable();
            $table->integer('qty');
            $table->string('status')->nullable();
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
        Schema::dropIfExists('detail_returs');
    }
}
