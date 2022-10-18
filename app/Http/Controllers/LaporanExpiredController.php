<?php

namespace App\Http\Controllers;

// use App\Models\LaporanExpired;
use App\Models\Barang;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;
use App\Exports\LaporanExpiredExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanExpiredController extends Controller{
	public function print(){
		return Excel::download(new LaporanExpiredExport, 'Cetak Laporan Expired.xlsx');
	}

	public function index(){
		$laporanexpired = Barang::with(['stok_barang','satuan'])->get();
		return view('laporanexpired.list', compact('laporanexpired'));
	}

	public function getLaporanExpiredList(Request $request){
		$kategori = $request->kategori;
		$dateToday = $request->dateToday;
		$startDate = $request->startDate;
		$endDate = $request->endDate;
		$onlyMonth = $request->onlyMonth;
		$currentDatePlus = date('Y-m-d', strtotime("+180 days", strtotime(date('Y-m-d'))));
		if(request()->ajax()){
			if($kategori=='today' && !empty($dateToday)){
				$data = StokBarang::with([
					'barang'=>function($query){
						$query->with('satuan');
					},
				])->where([
					['expired','<=',$currentDatePlus],
					['tgl_masuk',$dateToday],
				])->get();
			}elseif($kategori=='between' && !empty($startDate) && !empty($endDate)){
				$data = StokBarang::with([
					'barang'=>function($query){
						$query->with('satuan');
					},
				])->where('expired','<=',$currentDatePlus)
				->whereBetween('tgl_masuk',[$startDate,$endDate])
				->get();
			}elseif($kategori=='month' && !empty($onlyMonth)){
				$data = StokBarang::with([
					'barang'=>function($query){
						$query->with('satuan');
					},
				])->where([
					['expired','<=',$currentDatePlus],
					['tgl_masuk','like',$onlyMonth.'%'],
				])->get();
			}else{
				$data = StokBarang::with([
					'barang'=>function($query){
						$query->with('satuan');
					},
				])
				->where('expired','<=',$currentDatePlus)
				->get();
			}
			return datatables()->of($data)->addIndexColumn()->make(true);
		}

		// return Datatables::of($data)
		// 	->addColumn('action', function ($data) {
		// 		if ($data->name == 'Super Admin') {
		// 			return '';
		// 		}
		// 		if (Auth::user()->can('manage_user')) {
		// 			return '<div class="table-actions">
		// 			<a href="#productView" data-toggle="modal" data-target="#productView" id="detail"><i
		// 			class="ik ik-eye f-16 mr-15"></i></a>
		// 			<a href="'.url('laporanexpired/edit/'.$data->id).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
		// 			<a href="'.url('laporanexpired/'.$data->id).'"><i class="ik ik-trash-2 f-16 text-red"></i></a>
		// 			</div>';
		// 		} else {
		// 			return '';
		// 		}
		// 	})
		// 	->rawColumns(['action'])
		// 	->make(true);
	}

	public function create(){
	}

	public function store(Request $request){
	}

	public function show($id){
	}

	public function edit($id){
		$laporanexpired = Barang::find($id);
		return view('laporanexpired.edit', compact('laporanexpired'));
	}
}
