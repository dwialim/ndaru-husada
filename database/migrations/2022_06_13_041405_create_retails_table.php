<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retails', function (Blueprint $table) {
            $table->id();
            $table->enum('nama_barang', ['Amoxillin 500 mg tb (Pcs)' , 'Amoxillin 500 mg capsul (Pcs)'])->nullable();
            $table->string('no_bacth')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('harga_beli')->nullable();
            $table->string('harga_jual')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->string('tanggal_expired')->nullable();
            $table->string('laba')->nullable();
            $table->string('pajak')->nullable();
            $table->enum('supplier', ['AT.Agsg', 'Asfa Afasf'])->nullable();
            $table->enum('status', ['Aman' , 'Expired'])->nullable();
            $table->string('stock_awal')->nullable();
            $table->string('sisa_stock')->nullable();
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
        Schema::dropIfExists('retails');
    }
}
