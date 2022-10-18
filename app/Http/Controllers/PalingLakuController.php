<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use App\Models\PalingLaku;
use Illuminate\Http\Request;
use App\Exports\PalingLakuExport;
use App\Models\DetailPenjualan;
use Maatwebsite\Excel\Facades\Excel;

class PalingLakuController extends Controller{
	// BELUM TERPAKAI
	// public function print(){
	// 	$date = date('Y-m-d');
	// 	$data['date'] = $date;
	// 	$data['judul'] = 'LAPORAN BARANG PALING LAKU';
	// 	$data['lap'] = DetailPenjualan::has('penjualan')->has('stok_barang')
	// 		->selectRaw('detail_penjualans.*, stok_barangs.barang_id, barangs.nama as nama_barang, barangs.jenis as jenis_barang, satuans.id as id_satuan, satuans.nama as nama_satuan, SUM(qty) as qty')
	// 		->join('stok_barangs', 'detail_penjualans.stok_barang_id', 'stok_barangs.id')
	// 		->join('barangs', 'stok_barangs.barang_id', 'barangs.id')
	// 		->join('satuans', 'barangs.satuan_id', 'satuans.id')
	// 		->groupBy('barang_id')
	// 		->orderBy('qty', 'desc')
	// 		->get();

	// 	$date = date('Y-m-d');
	// 	return Excel::download(new PalingLakuExport($data), "Laporan Paling Laku ".$date.".xlsx");
	// }

	public function index(Request $request){
		$startDate = $request->startDate;
		$endDate = $request->endDate;
		if ($request->ajax()) {
			if(!empty($startDate) && !empty($endDate)){
				$data = DetailPenjualan::whereHas('penjualan',function($query) use($startDate,$endDate){
					$query->whereBetween('tanggal_penjualan',[$startDate,$endDate]);
				})->has('stok_barang')
				->selectRaw('detail_penjualans.*, stok_barangs.barang_id, barangs.nama as nama_barang, barangs.jenis as jenis_barang, satuans.id as id_satuan, satuans.nama as nama_satuan, SUM(qty) as qty')
				->join('stok_barangs', 'detail_penjualans.stok_barang_id', 'stok_barangs.id')
				->join('barangs', 'stok_barangs.barang_id', 'barangs.id')
				->join('satuans', 'barangs.satuan_id', 'satuans.id')
				->groupBy('barang_id')
				->orderBy('qty', 'desc')
				->get();
			}else{
				$data = DetailPenjualan::whereHas('penjualan')->has('stok_barang')
				->selectRaw('detail_penjualans.*, stok_barangs.barang_id, barangs.nama as nama_barang, barangs.jenis as jenis_barang, satuans.id as id_satuan, satuans.nama as nama_satuan, SUM(qty) as qty')
				->join('stok_barangs', 'detail_penjualans.stok_barang_id', 'stok_barangs.id')
				->join('barangs', 'stok_barangs.barang_id', 'barangs.id')
				->join('satuans', 'barangs.satuan_id', 'satuans.id')
				->groupBy('barang_id')
				->orderBy('qty', 'desc')
				->get();
			}
			return Datatables::of($data)
			->addIndexColumn()
			->make(true);
		}
		return view('palinglaku.main');
	}

	public function create(){
	}

	public function store(Request $request){
	}

	public function show($id){
	}

	public function edit(PalingLaku $palingLaku){
	}

	public function update(Request $request, PalingLaku $palingLaku){
	}
}