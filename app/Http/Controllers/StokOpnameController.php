<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokOpname;
use App\Models\StokBarang;
use DataTables, Validator;

class StokOpnameController extends Controller{
	public function main(){
		return view('stok-opname.main');
	}

	public function form(Request $request){
		// $data = [];
		$data['kode'] = $this->generateKodeSO();
		$data['data'] = (!empty($request->id)) ? Retur::with(['detail_retur', 'user', 'penjualan'])->find($request->id) : "";
		$data['jenis'] = $request->jenis;
		$content = view('stok-opname.form', $data)->render();
		return ['status' => 'success', 'content' => $content, ];
	}

	public function getStokOpname(Request $request){
		if(request()->ajax()){
			$startDate = $request->startDate;
			$endDate = $request->endDate;
			if(!empty($startDate) && !empty($endDate)){
				$data = StokOpname::with([
					'stok_barang'=>function($query){
						$query->with('barang');
					}
				])
				->whereBetween('tanggal',[$startDate,$endDate])
				->get();
			}else{
				$data = StokOpname::with([
					'stok_barang'=>function($query){
						$query->with('barang');
					}
				])->get();
			}
			return Datatables::of($data)
				->addIndexColumn()
				->addColumn('hargaPerBiji',function($raw){
					$hBeli = $raw->stok_barang->harga_beli;
					if($hBeli==0 || $raw->stok_barang->jumlah_perbox==0){
						// return $hPerBiji = round($hBeli/$raw->stok_barang->stok_awal); // koding awal 20-09-2022
						return $hPerBiji = 0;
					}else{
						return $hPerBiji = round($hBeli/$raw->stok_barang->jumlah_perbox);
					}
				})
				->rawColumns(['hargaPerBiji'])
				->make(true);
		}
	}

	public function getStokBarang(Request $request){
		$kode = $this->generateKodeSO();
		$query = $request->q ?? '###';
		if(!empty($request->id)){
			$data = StokBarang::with('barang')
				->where('id',$request->id)->first();
			return ['status'=>'success','data'=>$data,'kode'=>$kode];
		}else{
			// versi 1.0 cari data (hanya No.Batch)
				// $data = StokBarang::select(['id' ,'no_batch'])
				// 	->where('no_batch', 'like', "%$query%")
				// 	->get();

			// versi 1.3 cari data (No.Batch dan barcode)
				$data = StokBarang::with('barang')
					->where(function($q)use($query){
						$q->where('no_batch','like',"%$query%")
							->orWhere('barcode','like',"%$query%");
					})
					->get();
					// $data = $query;
		}
		return response()->json($data, 200);
	}

	// VERSI 1.0 (belum ada update stok)
		// public function store(Request $request){
		// 	$rules = [
		// 		'jumlahBox.*' => 'required',
		// 		'jumlahPerBox.*' => 'required',
		// 	];

		// 	$messages = [
		// 		'required' => 'Kolom Haurs Diisi',
		// 	];

		// 	$validator = Validator::make($request->all(),$rules,$messages);
		// 	if(!$validator->fails()){
		// 		foreach($request->noBatch as $key => $val){
		// 			$stokBarang = StokBarang::find($request->stok_barang_id[$key]);
		// 			$data = new StokOpname;
		// 			if(!empty($request->deskripsi[$key])){
		// 				$data->keterangan = strtolower($request->deskripsi[$key]);
		// 			}
		// 			if(!empty($request->sisaStrip[$key])){
		// 				$data->sisa_strip= $request->sisaStrip[$key];
		// 				$jumlahStripBaru = ($request->jumlahBox[$key] * $request->jumlahPerBox[$key]) + $request->sisaStrip[$key];
		// 			}else{
		// 				$jumlahStripBaru = $request->jumlahBox[$key] * $request->jumlahPerBox[$key];
		// 			}
		// 			$selisih = $jumlahStripBaru - $stokBarang->jumlah;

		// 			$data->stok_barang_id	= $request->stok_barang_id[$key];
		// 			$data->batch_stok_barang= $request->noBatch[$key];
		// 			$data->kode_stok_opname	= $request->kodeSOP;
		// 			$data->jumlah_box			= $request->jumlahBox[$key];
		// 			$data->jumlah_perbox		= $request->jumlahPerBox[$key];
		// 			$data->jumlah_stok		= $jumlahStripBaru;
		// 			$data->selisih				= $selisih;
		// 			$data->tanggal				= $request->tanggal;
		// 			$data->save();
		// 		}
		// 		// return $data;
		// 		return ['status'=>'success','message'=>'Data berhasil diPosting!'];
		// 	}else{
		// 		return $validator->messages();
		// 	}
		// }

	// VERSI 1.3 (sudah ada update stok)
	public function store(Request $request){
		$rules = [
			'jumlahBox.*' => 'required',
			'jumlahPerBox.*' => 'required',
		];

		$messages = [
			'required' => 'Kolom Haurs Diisi',
		];

		$validator = Validator::make($request->all(),$rules,$messages);
		if(!$validator->fails()){
			foreach($request->noBatch as $key => $val){
				$stokBarang = StokBarang::find($request->stok_barang_id[$key]);
				$data = new StokOpname;
				if(!empty($request->deskripsi[$key])){
					$data->keterangan = strtolower($request->deskripsi[$key]);
				}
				if(!empty($request->sisaStrip[$key])){
					$data->sisa_strip= $request->sisaStrip[$key];
					$jumlahStripBaru = ($request->jumlahBox[$key] * $request->jumlahPerBox[$key]) + $request->sisaStrip[$key];
				}else{
					$jumlahStripBaru = $request->jumlahBox[$key] * $request->jumlahPerBox[$key];
				}
				$selisih = $jumlahStripBaru - $stokBarang->jumlah;

				$data->stok_awal			= $stokBarang->jumlah;
				$data->stok_terbaru		= $jumlahStripBaru;
				$data->stok_barang_id	= $request->stok_barang_id[$key];
				$data->batch_stok_barang= $request->noBatch[$key];
				$data->kode_stok_opname	= $request->kodeSOP;
				$data->jumlah_box			= $request->jumlahBox[$key];
				$data->jumlah_perbox		= $request->jumlahPerBox[$key];
				$data->jumlah_stok		= $jumlahStripBaru;
				$data->selisih				= $selisih;
				$data->tanggal				= $request->tanggal;
				$data->save();

				$stokBarang->jumlah_box = $request->jumlahBox[$key];
				$stokBarang->jumlah_perbox = $request->jumlahPerBox[$key];
				$stokBarang->jumlah = $jumlahStripBaru;
				$stokBarang->save();

			}
			return ['status'=>'success','message'=>'Data berhasil diPosting!'];
		}else{
			return $validator->messages();
		}
	}
}
