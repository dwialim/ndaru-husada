<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\StokBarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;

class StokImport implements ToCollection,WithStartRow{
	public function collection(Collection $rows){
		// ROW 0 NAMA
		// ROW 1 Satuan
		// ROW 3 hna_ppn
		// ROW 4 hna
		// ROW 5 harga_beli
		// ROW 6 harga_jual
		// echo "<pre>";
		// print_r($rows);
		// echo "</pre>";
		// exit();

		// ROW 0 noBatch
		// ROW 1 nama
		// ROW 2 satuan
		// ROW 3 perBox(isi)
		// ROW 4 stokAwal
		// ROW 5 hargaBeli perBox
		// ROW 6 ED

		$controller = new Controller;
		foreach ($rows as $row){
			// $exDate = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]));
			$noBatch = $row[0];
			$perBox = $row[3];
			$stokAwal = $row[4];
			$hargaBeli = $row[6];
			$umum = $row[7];
			$resep = $row[8];
			$dispenBox = $row[9];
			$dispenBiji = $row[10];
			if(!empty($noBatch)){
				if($noBatch!="STOKBERMASALAH"){
					$awal = $stokAwal;
					$perbox = $perBox;
					if($awal==0){
						$jumlah = 0;
						$jumlahBox = 0;
					}else{
						$mod = $awal%$perbox;
						$box = ($awal-$mod)/$perbox;
						$biji = $awal;
						$jumlah = $awal;
						$jumlahBox = $box;
					}
					// foreach($dt as $k => $p){
					// 	$sb = StokBarang::where('id',$p->id)->first();
					// 	$awal = $sb->stok_awal;
					// 	$perbox = $sb->jumlah_perbox;
					// 	if($sb->stok_awal==0){
					// 		$sb->jumlah = $awal;
					// 		$sb->jumlah_box = $awal;
					// 	}else{
					// 		$mod = $awal%$perbox;
					// 		$box = ($awal-$mod)/$perbox;
					// 		$biji = $awal;
					// 		$sb->jumlah = $awal;
					// 		$sb->jumlah_box = $box;
					// 	}
					// 	$sb->save();
					// }

					// $data = StokBarang::where('no_batch','LIKE',"%$noBatch")->first();
					$data = StokBarang::where('no_batch',$noBatch)->first();
					if(!empty($data)){
						$data->jumlah_box = $jumlahBox;
						$data->jumlah_perbox = $perBox;
						$data->jumlah     = $jumlah;
						$data->stok_awal  = $stokAwal;
						$data->no_batch   = $noBatch;
						$data->harga_beli = $hargaBeli;
						
						$data->harga_umum = $umum;
						$data->harga_resep = $resep;
						$data->harga_dispensing = $dispenBox;
						$data->harga_dispensing_perbiji = $dispenBiji;

						// $data->expired = date("Y-m-d",strtotime($exDate));
						// $data->tgl_masuk = date("Y-m-d",strtotime("03-10-2022"));
						// $data->minimal_stok = 5;
						$data->save();
					}
				}
				// $stok = new StokBarang;
				// $stok->barang_id = $row[0];
				// $stok->jumlah_box = 1;
				// $stok->jumlah_perbox = $row[4];
				// $stok->jumlah = 1*$row[4];
				// $stok->stok_awal = 1*$row[4];
				// $stok->no_batch = $row[2];
				// $stok->harga_beli = $row[5];
				// $stok->harga_umum = $row[6];
				// $stok->harga_resep = $row[9];
				// $stok->harga_dispensing = $row[7];
				// $stok->harga_dispensing_perbiji = $row[8];
				// $stok->expired = $exDate;
				// $stok->tgl_masuk = date("Y-m-d",strtotime("03-10-2022"));
				// $stok->minimal_stok = 5;
				// $stok->save();
			}
		}
	}
	public function startRow(): int
	{
		return 1;
	}
}
