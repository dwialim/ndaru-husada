<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstPersentase;

use DataTables, Validator;

class MstPersentaseController extends Controller{
	public function index(){
		return view('master.persentase.main');
	}

	public function dataTbMstPersentase(Request $request){
		if(request()->ajax()){
			$data = MstPersentase::all();
			return dataTables::of($data)
				->addColumn('action',function($row){
					$btn = '<a href="javascript:void(0);" onclick=updated('.$row->id.')><i class="ik ik-edit f-16 mr-15 text-warning"></i></a>';
					// $btn .= '<a href="javascript:void(0);" onclick=deleted('.$row->id.')><i class="ik ik-trash-2 mr-15 f-16 text-danger"></i></a>';
					return $btn;
				})
				->addIndexColumn()->rawColumns(['action'])
				->make(true);
		}
	}

	public function form(Request $request){
		// return $request->all();
		if(isset($request->id)){
			$data['title'] = 'Edit';
			$data['data'] = MstPersentase::find($request->id);
		}else{
			$data['title'] = 'Tambah';
			$data['data'] = '';
		}
		
		$content = view('master.persentase.form', $data)->render();
		return ['status'=>'success','content'=>$content];
	}

	public function store(Request $request){
		$rules = [
			'nama'=> 'required',
			'persentase'=> 'required',
		];

		$messages = [
			'required' => 'Kolom Harus Diisi',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		if(!$validator->fails()){
			if($request->id != ''){
				$save = MstPersentase::find($request->id);
			}else{
				$save = new MstPersentase;
			}
			$save->nama = $request->nama;
			$save->persentase = $request->persentase;
			if(!empty($request->nominal)){
				$save->nominal = preg_replace("/\D+/", "", $request->nominal);
			}else{
				$save->nominal = null;
			}
			$save->save();
			$return = ['status'=>'success', 'code'=>'200', 'message'=>'Data Berhasil Disimpan !!'];
			return response()->json($return);
		}else{
			return $validator->messages();
		}
	}

	public function destroy(Request $request){

	}
}
