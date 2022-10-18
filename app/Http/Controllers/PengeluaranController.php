<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pengeluaran;
use DataTables, Validator;

class PengeluaranController extends Controller{
	public function index(){
		return view('laporanpengeluaran.main');
	}

	public function form(Request $request){
		if(isset($request->id)){
			$data['title'] = 'Edit';
			$data['data'] = Pengeluaran::find($request->id);
		}else{
			$data['title'] = 'Tambah';
			$data['data'] = '';
		}
		$content = view('laporanpengeluaran.form',$data)->render();
		return ['status'=>'success','content'=>$content];
	}

	public function getPengeluaran(Request $request){
		if(request()->ajax()){
			$startDate = $request->startDate;
			$endDate = $request->endDate;
			if(!empty($startDate) && !empty($endDate)){
				$data = Pengeluaran::whereBetween('tanggal',[$startDate,$endDate])->get();
			}else{
				$data = Pengeluaran::all();
			}

			$sumPengeluaran = 0;
			foreach($data as $key => $val){
				$sumPengeluaran += $val->nominal;
			}

			return Datatables::of($data)
				->addIndexColumn()
				->addColumn('action',function($row){
					$btn = '<a href="javascript:;" onclick=updated('.$row->id.')><i class="ik ik-edit f-16 mr-15 text-warning"></i></a>';
					$btn .= '<a href="javascript:;" onclick=deleted('.$row->id.')><i class="ik ik-trash-2 f-16 text-danger"></i></a>';
					return $btn;
				})
				->with('sumPengeluaran',$sumPengeluaran)
				->make(true);
		}
	}

	public function store(Request $request){
		$rules = [
			'nama'=> 'required',
			'nominal'=> 'required',
			'tanggal'=> 'required',
		];

		$messages = [
			'required' => 'Kolom Harus Diisi',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		if(!$validator->fails()){
			if(!empty($request->id)){
				$data = Pengeluaran::find($request->id);
			}else{
				$data = new Pengeluaran;
			}
			$data->nama = $request->nama;
			if(!empty($request->deskripsi)){
				$data->deskripsi = $request->deskripsi;
			}
			$data->nominal = preg_replace("/\D+/", "", $request->nominal);
			$data->tanggal = date('Y-m-d',strtotime($request->tanggal));
			$data->save();

			return ['status'=>'success','message'=>'Data berhasil ditambahkan'];
		}else{
			return $validator->messages();
		}
	}

	public function destroy(Request $request){
		$data = Pengeluaran::find($request->id);
		if($data){
			$data->delete();
			$return = ['status'=>'success','message'=>'Data berhasil dihapus!'];
		}else{
			$return = ['status'=>'error','message'=>'Gagal menghapus data!'];
		}
		return $return;
	}
}
