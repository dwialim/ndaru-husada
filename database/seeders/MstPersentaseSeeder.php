<?php

namespace Database\Seeders;

use App\Models\MstPersentase;
use Illuminate\Database\Seeder;

class MstPersentaseSeeder extends Seeder{
	public function run(){
		MstPersentase::create([
			'id'			=> '1',
			'nama'		=> 'Harga Umum',
			'persentase'=> '25',
			'nominal'	=> null,
		]);

		MstPersentase::create([
			'id'			=> '2',
			'nama'		=> 'Harga Resep',
			'persentase'=> '20',
			'nominal'	=> '4000',
		]);

		MstPersentase::create([
			'id'			=> '3',
			'nama'		=> 'Harga Dispensing (Per Box)',
			'persentase'=> '20',
			'nominal'	=> null,
		]);

		MstPersentase::create([
			'id'			=> '4',
			'nama'		=> 'Harga Dispensing (Per Biji)',
			'persentase'=> '22',
			'nominal'	=> null,
		]);
	}
}
