<?php

namespace Database\Seeders;

use App\Models\StokBarang;
use Illuminate\Database\Seeder;

class StokBarangSeeder extends Seeder{
	public function run(){
		StokBarang::create([ 
			'id'								=> '1',
			'barang_id'						=> '1',
			'jumlah_box'					=> '10',
			'jumlah_perbox'				=> '20',
			'jumlah'							=> '200',
			'stok_awal'						=> '200',
			'no_batch'						=> '351',
			'harga_beli'					=> '10000',
			'harga_umum'					=> '125',
			'harga_resep'					=> '4120',
			'harga_dispensing'			=> '120',
			'harga_dispensing_perbiji'	=> '122',
			'expired'						=> '2022-08-19',
			'tgl_masuk'						=> '2022-06-13',
			'minimal_stok'					=> '25',
			'nominal_laba'					=> null,
			'pajak_id'						=> null,
			'nominal_pajak'				=> null,
			'pbf_id'							=> '1'
		]);

		StokBarang::create([ 
			'id'								=> '2',
			'barang_id'						=> '2',
			'jumlah_box'					=> '10',
			'jumlah_perbox'				=> '20',
			'jumlah'							=> '200',
			'stok_awal'						=> '200',
			'no_batch'						=> '351',
			'harga_beli'					=> '10000',
			'harga_umum'					=> '125',
			'harga_resep'					=> '4120',
			'harga_dispensing'			=> '120',
			'harga_dispensing_perbiji'	=> '122',
			'expired'						=> '2022-08-19',
			'tgl_masuk'						=> '2022-06-13',
			'minimal_stok'					=> '27',
			'nominal_laba'					=> null,
			'pajak_id'						=> null,
			'nominal_pajak'				=> null,
			'pbf_id'							=> '1'
		]);

		StokBarang::create([ 
			'id'								=> '3',
			'barang_id'						=> '3',
			'jumlah_box'					=> '10',
			'jumlah_perbox'				=> '20',
			'jumlah'							=> '200',
			'stok_awal'						=> '200',
			'no_batch'						=> '351',
			'harga_beli'					=> '10000',
			'harga_umum'					=> '125',
			'harga_resep'					=> '4120',
			'harga_dispensing'			=> '120',
			'harga_dispensing_perbiji'	=> '122',
			'expired'						=> '2022-08-19',
			'tgl_masuk'						=> '2022-06-13',
			'minimal_stok'					=> '33',
			'nominal_laba'					=> null,
			'pajak_id'						=> null,
			'nominal_pajak'				=> null,
			'pbf_id'							=> '1'
		]);

	}
}