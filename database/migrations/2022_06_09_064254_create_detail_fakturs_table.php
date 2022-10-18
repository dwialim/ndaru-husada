<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailFaktursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_fakturs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faktur_id');
            $table->foreignId('pajak_id');
            $table->integer('nominal')->nullable();
            $table->integer('persentase')->nullable();
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
        Schema::dropIfExists('detail_fakturs');
    }
}
