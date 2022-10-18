<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PersediaanLaporans extends Command{
	// the name and signature of the console command.
	protected $signature = 'laporans:persediaan';

	/**
	* The console command description.
	*
	* @var string
	*/
	protected $description = 'Create Laporan Persediaan';

	// buat perintah baru
	public function __construct(){
		parent::__construct();
	}

	// eksekusi perintah
	public function handle(){
		$barang = DB::table('stok_barangs')->all();

	}
}
