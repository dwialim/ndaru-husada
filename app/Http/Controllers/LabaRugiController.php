<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use App\Models\LabaRugi;
use Illuminate\Http\Request;
use App\Exports\LabaRugiExport;
use App\Models\DetailPenjualan;
use App\Exports\PalingLakuExport;
use Maatwebsite\Excel\Facades\Excel;

class LabaRugiController extends Controller{
	// buat private property(konsep OOP, hanya dapat di akses di class itu sendiri)
	private $total;

	// MULAI HITUNG LABA BERSIH
	public function hitungLabaRugi($harga,$value, $perBox){
		$total = 0;
		if($value->jenis_penjualan =="Dispensing (perBox)"){
			$selisih = round($harga - $value->stok_barang->harga_beli);
			$labarugi = $selisih * ($value->qty/$perBox);
			$omset = $harga*($value->qty/$perBox);
		}else{
			$selisih = round($harga - ($value->stok_barang->harga_beli/$perBox));
			$labarugi = $selisih * $value->qty;
			$omset = $harga*$value->qty;
		}

		$value->selisih = $selisih;
		$value->labarugi = $labarugi;
		$value->omset = $omset;
		$total += $labarugi;
		$this->total += $total;
	}

	// PENGECEKAN JENIS PENJUALAN
	public function cekJenisPenjualan($data){
		foreach($data as $key => $value){
			$perBox = $value->stok_barang->jumlah_perbox;
			if($value->jenis_penjualan =="Umum"){
				$this->hitungLabaRugi($value->stok_barang->harga_umum, $value, $perBox);
			}elseif($value->jenis_penjualan =="Resep"){
				$this->hitungLabaRugi($value->stok_barang->harga_resep, $value, $perBox);
			}elseif($value->jenis_penjualan =="Dispensing (perBox)"){
				$this->hitungLabaRugi($value->stok_barang->harga_dispensing, $value, $perBox);
			}elseif($value->jenis_penjualan =="Dispensing (perBiji)"){
				$this->hitungLabaRugi($value->stok_barang->harga_dispensing_perbiji, $value, $perBox);
			}
		}
	}

	// GET DATA
	public function query($startDate,$endDate){
		if(!empty($startDate) && !empty($endDate)){
			$data = DetailPenjualan::whereHas('penjualan',function($query) use($startDate,$endDate){
				$query->whereBetween('tanggal_penjualan',[$startDate,$endDate]);
			})->has('stok_barang')
			->with('stok_barang.barang.satuan')
			->get();
		}
		else{
			$data = DetailPenjualan::has('penjualan')->has('stok_barang')
			->with('stok_barang.barang.satuan')
			->get();
		}
		$this->data = $data; // SET PROPERTY DATA SUPAYA DAPAT DI AKSES DI KETIKA METHOD DIPANGGIL
	}

	public function print(Request $request) {
		$startDate = $request->startDate;
		$endDate = $request->endDate;
		$data['totalLabaRugi'] = preg_replace("/\D+/", "", $request->totalLabaRugi);
		$date = date('Y-m-d');
		$data['date'] = $date;
		$data['judul'] = 'LAPORAN LABA RUGI';

		$this->query($startDate,$endDate); // GET DATA FROM FUNCTION
		$this->cekJenisPenjualan($this->data); // CEK JENIS PENJUALAN
		
		$data['lap'] = $this->data;
		// $data['total'] = $total;

		$date = date('Y-m-d');

		$content = view('labarugi.excel', $data)->render();
		return ['status' => 'success', 'content' => $content];
		// return Excel::download(new LabaRugiExport($data), "Laporan Laba Rugi ".$date.".xlsx");
	}

	public function index(Request $request){
		$startDate = $request->startDate;
		$endDate = $request->endDate;

		$this->query($startDate,$endDate); // GET DATA FROM FUNCTION
		$this->cekJenisPenjualan($this->data); // CEK JENIS PENJUALAN
		
		if ($request->ajax()) {
			
			// return $data;
			return Datatables::of($this->data)
				->addIndexColumn()
				->addColumn('total', "$this->total")
				->editColumn('barang', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
						->stok_barang
						->barang
						->nama ?? 'Null' ;
				})
				->editColumn('satuan', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->barang
					->satuan
					->nama ?? 'Null' ;
				})
				->editColumn('jenis', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->barang
					->jenis ?? 'Null' ;
				})
				->editColumn('expired', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->expired ?? 'Null' ;
				})
				->editColumn('jenis_penjualan', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->jenis_penjualan ?? 'Null' ;
				})
				->editColumn('harga_beli', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_beli ?? 'Null' ;
				})
				->editColumn('harga_umum', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_umum ?? 0 ;
				})
				->editColumn('harga_resep', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_resep ?? 'Null' ;
				})
				->editColumn('harga_dispensing', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_dispensing ?? 'Null' ;
				})
				->editColumn('harga_dispensing_perbiji', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_dispensing_perbiji ?? 'Null' ;
				})
				->editColumn('jumlah_perbox', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->jumlah_perbox ?? 'Null' ;
				})
				->with('total',$this->total)
				->make(true);
		}

		return view('labarugi.main', $data = ['total' => $this->total]);
	}

	public function show($id){
	}

	public function edit($id){
		$labarugi = LabaRugi::find($id);
		return view('labarugi.edit', compact('labarugi'));
	}
}