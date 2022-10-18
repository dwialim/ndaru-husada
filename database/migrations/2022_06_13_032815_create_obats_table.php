<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->enum('nama_obat', ['Amoxillin 500 mg tb (Pcs)' , 'Amoxillin 500 mg capsul (Pcs)'])->nullable();
            $table->string('no_bacth')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('supplier')->nullable();
            $table->string('harga_beli')->nullable();
            $table->string('harga_jual')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->string('tanggal_expired')->nullable();
            $table->string('laba')->nullable();
            $table->string('pajak')->nullable();
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
        Schema::dropIfExists('obats');
    }
}
