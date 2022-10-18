<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use App\Models\LabaBersih;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use App\Exports\LabaBersihExport;
use Maatwebsite\Excel\Facades\Excel;

class LabaBersihController extends Controller{
	// buat private property(konsep OOP, hanya dapat di akses di class itu sendiri)
	private $total;

	// GET DATA
	public function query($startDate,$endDate){
		if(!empty($startDate) && !empty($endDate)){
			$data = DetailPenjualan::whereHas('penjualan',function($query) use($startDate,$endDate){
				$query->whereBetween('tanggal_penjualan',[$startDate,$endDate]);
			})->has('stok_barang')
			->with([
				'stok_barang'=>function($query){
					$query->with(['faktur.detail_faktur','barang.satuan']);
				},
			])
			->get();
		}else{
			$data = DetailPenjualan::has('penjualan')->has('stok_barang')
			// ->with('stok_barang.barang.satuan')
			->with([
				'stok_barang'=>function($query){
					$query->with(['faktur.detail_faktur','barang.satuan']);
				},
			])
			->get();
		}
		$this->data = $data; // SET PROPERTY DATA SUPAYA DAPAT DI AKSES KETIKA METHOD DIPANGGIL
	}

	// MULAI HITUNG LABA BERSIH
	public function hitungLabaBersih($harga,$value,$perBox){
		$total = 0;
		$hargaBeli = $value->stok_barang->harga_beli;
		$getDF = $value->stok_barang->faktur;

		$nominal = 0;
		if(isset($getDF->detail_faktur)){ // HITUNG LABA BERSIH DENGAN PAJAK
			foreach ($getDF->detail_faktur as $key => $val) {
				if(isset($val->nominal)){
					$nominal += round((($val->nominal/$getDF->total_pembelian)*100),2);
				}else{
					$nominal += $val->persentase;
				}
			}
			if($value->jenis_penjualan =="Dispensing (perBox)"){
				$selisih = round($harga - $hargaBeli);
				$pajak = round($hargaBeli*($nominal/100));
				$lababersih = round(($harga - $pajak - $hargaBeli)*($value->qty/$perBox));
				$omset = $harga*($value->qty/$perBox);
			}else{
				$selisih = round($harga - ($hargaBeli/$perBox));
				$hargaBiji = round($hargaBeli/$perBox);
				$pajak = round($hargaBiji*($nominal/100));
				$lababersih = round(($harga - $pajak - $hargaBiji) * $value->qty);
				$omset = $harga*$value->qty;
			}

		}else{ // HITUNG LABA BERSIH TANPA PAJAK
			if($value->jenis_penjualan =="Dispensing (perBox)"){
				$selisih = round($harga - $hargaBeli);
				$lababersih = round(($harga - $hargaBeli)*($value->qty/$perBox));
				$omset = $harga*($value->qty/$perBox);
			}else{
				$selisih = round($harga - ($hargaBeli/$perBox));
				$hargaBiji = round(($hargaBeli/$perBox),1);
				$lababersih = ($harga - $hargaBiji)*$value->qty;
				$omset = $harga*$value->qty;
			}
		}
		$value->lababersih = $lababersih;
		$value->omset = $omset;
		$value->pajak = $pajak;
		$value->selisih = $selisih;
		$total += $lababersih;
		$this->total += $total;
	}

	// PENGECEKAN JENIS PENJUALAN
	public function cekJenisPenjualan($data){
		foreach ($data as $key => $value) {
			$perBox = $value->stok_barang->jumlah_perbox;
			if($value->jenis_penjualan =="Umum"){ // HITUNG LABA PENJUALAN UMUM
				$this->hitungLabaBersih($value->stok_barang->harga_umum, $value, $perBox);
			}elseif($value->jenis_penjualan =="Resep"){ // HITUNG LABA PENJUALAN RESEP
				$this->hitungLabaBersih($value->stok_barang->harga_resep, $value, $perBox);
			}elseif($value->jenis_penjualan =="Dispensing (perBox)"){ // HITUNG LABA PENJUALAN DISPENSING BOX
				$this->hitungLabaBersih($value->stok_barang->harga_dispensing, $value, $perBox);
			}elseif($value->jenis_penjualan =="Dispensing (perBiji)"){ // HITUNG LABA PENJUALAN DISPENSING BIJI
				$this->hitungLabaBersih($value->stok_barang->harga_dispensing_perbiji, $value, $perBox);
			}
		}
	}

	public function print(Request $request){
		$date = date('Y-m-d');
		$data['date'] = $date;
		$data['judul'] = 'LAPORAN LABA BERSIH';

		$startDate = $request->startDate;
		$endDate = $request->endDate;

		$this->query($startDate,$endDate); // GET DATA FROM FUNCTION
		$this->cekJenisPenjualan($this->data); // CEK JENIS PENJUALAN

		// $data['lap'] = $temp;
		$data['lap'] = $this->data;
		$data['total'] = $this->total;

		$date = date('Y-m-d');

		$content = view('lababersih.excel', $data)->render();
		return ['status' => 'success', 'content' => $content];
		// return Excel::download(new LabaBersihExport($data), "Laporan Laba Bersih ".$date.".xlsx");
	}

	public function index(Request $request){
		$startDate = $request->startDate;
		$endDate = $request->endDate;

		$this->query($startDate,$endDate); // GET DATA FROM FUNCTION

		$this->cekJenisPenjualan($this->data); // CEK JENIS PENJUALAN

		if ($request->ajax()) {
			return Datatables::of($this->data)
			->addIndexColumn()
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
		return view('lababersih.main', $data = ['total' => $this->total]);
	}

	public function create(){
	}

	public function store(Request $request){
	}

	public function show($id){
	}

	public function edit($id){
		$lababersih = LabaBersih::find($id);
		return view('lababersih.edit', compact('lababersih'));
	}

	public function update(Request $request, LabaBersih $labaBersih){
	}
}