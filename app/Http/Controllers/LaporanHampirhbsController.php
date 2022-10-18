<?php

namespace App\Http\Controllers;

// use App\Models\LaporanHampirhbs;
use App\Models\Barang;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;
use App\Exports\HampirHabisExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanHampirhbsController extends Controller{
	public function print(){
		return Excel::download(new HampirHabisExport(), 'Cetak Laporan Hampir Habis.xlsx');
	}

	public function index(){
		// $laporanhampirhbs = LaporanHampirhbs::all();
		$laporanhampirhbs = Barang::with(['stok_barang','satuan'])->get();
		return view('laporanhampirhbs.list', compact('laporanhampirhbs'));
	}

	public function getLaporanHampirhbsList(Request $request){
		$kategori = $request->kategori;
		$dateToday = $request->dateToday;
		$startDate = $request->startDate;
		$endDate = $request->endDate;
		$onlyMonth = $request->onlyMonth;
		// $currentDatePlus = date('Y-m-d', strtotime("+180 days", strtotime(date('Y-m-d'))));
		if(request()->ajax()){
			if($kategori=='today' && !empty($dateToday)){
				$data = StokBarang::with([
					'barang'=>function($query){
						$query->with('satuan');
					},
				])
				->whereColumn('jumlah','<=','minimal_stok')
				->where('tgl_masuk',$dateToday)
				->get();
			}elseif($kategori=='between' && !empty($startDate) && !empty($endDate)){
				$data = StokBarang::with([
					'barang'=>function($query){
						$query->with('satuan');
					},
				])
				->whereColumn('jumlah','<=','minimal_stok')
				->whereBetween('tgl_masuk',[$startDate,$endDate])
				->get();
			}elseif($kategori=='month' && !empty($onlyMonth)){
				$data = StokBarang::with([
					'barang'=>function($query){
						$query->with('satuan');
					},
				])
				->whereColumn('jumlah','<=','minimal_stok')
				->where('tgl_masuk','like',$onlyMonth.'%')
				->get();
			}else{
				$data = StokBarang::with([
					'barang'=>function($query){
						$query->with('satuan');
					},
				])
				->whereColumn('jumlah','<=','minimal_stok')
				->get();
			}
			return datatables()->of($data)->addIndexColumn()->make(true);
		}

		// $data = Barang::get();
		// return Datatables::of($data)
		// 	->addColumn('action', function ($data) {
		// 		if ($data->name == 'Super Admin') {
		// 			return '';
		// 		}
		// 		if (Auth::user()->can('manage_user')) {
		// 			return '<div class="table-actions">
		// 			<a href="#productView" data-toggle="modal" data-target="#productView" id="detail"><i
		// 			class="ik ik-eye f-16 mr-15"></i></a>
		// 			<a href="'.url('laporanhampirhbs/edit/'.$data->id).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
		// 			<a href="'.url('laporanhampirhbs/'.$data->id).'"><i class="ik ik-trash-2 f-16 text-red"></i></a>
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
		// $laporanhampirhbs = LaporanHampirhbs::find($id);
		// return view('laporanhampirhbs.edit', compact('laporanhampirhbs'));
		$laporanexpired = Barang::find($id);
		return view('laporanhampirhbs.edit', compact('laporanhampirhbs'));
	}
}