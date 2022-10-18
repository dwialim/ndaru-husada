<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaransTable extends Migration
{
	public function up()
	{
		Schema::create('pengeluarans', function (Blueprint $table) {
			$table->id();
			$table->string('nama');
			$table->text('deskripsi')->nullable();
			$table->integer('nominal');
			$table->date('tanggal');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('pengeluarans');
	}
}
